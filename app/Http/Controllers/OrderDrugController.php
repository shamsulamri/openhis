<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderDrug;
use Log;
use DB;
use Session;
use App\UnitMeasure as Unit;
use App\DrugDosage as Dosage;
use App\DrugRoute as Route;
use App\DrugFrequency as Frequency;
use App\DrugDisease;
use App\Period;
use App\Order;
use App\Consultation;
use App\Product;
use Auth;
use App\DrugPrescription;
use App\OrderMultiple;
use App\OrderHelper;
use App\StockHelper;
use App\OrderRoute;
use App\QueueLocation;
use App\EncounterHelper;
use App\Store;
use App\Drug;
use App\DojoUtility;

class OrderDrugController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$order_drugs = DB::table('order_drugs')
					->orderBy('drug_strength')
					->paginate($this->paginateValue);
			return view('order_drugs.index', [
					'order_drugs'=>$order_drugs
			]);
	}


	public function medications()
	{
			$consultation_id = Session::get('consultation_id');
			$consultation = Consultation::findOrFail($consultation_id);
			$encounter_id = $consultation->encounter_id;

			$medications = OrderDrug::orderBy('b.created_at')
						->leftJoin('orders as b', 'b.order_id', '=', 'order_drugs.order_id')
						->where('encounter_id', $encounter_id)
						->get();

			return view('order_drugs.medications', [
					'medications'=>$medications,
					'patient'=>$consultation->encounter->patient,
					'consultation'=>$consultation,
			]);
	}

	public function addDrug(Request $request)
	{

			$product = Product::find($request->drug_code);
			if ($product) {
					OrderHelper::orderItem($product, $request->cookie('ward'));
			}

			return $this->medicationTable();
	}

	public function renewDrug(Request $request)
	{
			$order_id = $request->order_id;
			$order = Order::find($order_id);
			$product = Product::find($order->product_code);
			$renew_drug = OrderDrug::where('order_id', $order_id)->first();
			if ($product) {
					OrderHelper::orderItem($product, $request->cookie('ward'), $renew_drug );
			}

			return $this->medicationTable();
	}

	public function removeDrug(Request $request)
	{
			$order_id = $request->order_id;
			$order = Order::find($order_id);
			OrderMultiple::where('order_id', $order_id)->delete();
			Order::find($order_id)->delete();
					
			return $this->medicationTable();
	}

	public function updateDrug(Request $request)
	{
			$order_id = $request->order_id;
			Log::info($order_id);
			$drug = OrderDrug::where('order_id', $order_id)->first();
			if ($drug) {
					Log::info('Update drug............');
					$drug->drug_strength = $request->drug_strength;
					$drug->unit_code = $request->unit_code;
					$drug->drug_dosage = $request->drug_dosage;
					$drug->dosage_code = $request->dosage_code;
					$drug->route_code = $request->route_code;
					$drug->frequency_code = $request->frequency_code;
					$drug->drug_duration = $request->drug_duration;
					$drug->period_code = $request->period_code;

					Log::info($drug);
					$dosage = $drug->drug_dosage;
					$frequency = $drug->frequency?$drug->frequency->frequency_value:1;
					$period = $drug->period?$drug->period->period_mins:1;

					$total_unit = $dosage*$frequency;

					if ($drug->drug_duration>0) {
						$total_unit = $total_unit * (($drug->drug_duration*$period)/1440);
					}
					

					$order = Order::find($order_id);
					if ($order->product->product_unit_charge==0) {
							$total_unit = 1;
					}
					$order->order_quantity_request = $total_unit;
					$order->order_quantity_supply = $total_unit;
					$order->save();

					$drug->save();
			}

			//return $this->medicationTable();
	}

	public function addDrug2(Request $request)
	{

			$prescription = DrugPrescription::find($request->id);
			$product = Product::find($prescription->drug_code);
			if ($product) {
					OrderHelper::orderItem($product, $request->cookie('ward'), $prescription->prescription_id);
			}

			return $this->medicationTable();
	}

	function drugHistory() {

			$consultation_id = Session::get('consultation_id');
			$consultation = Consultation::findOrFail($consultation_id);
			$encounter_id = $consultation->encounter_id;

			$medications = OrderDrug::orderBy('b.created_at', 'desc')
						->select('order_drugs.unit_code as unit_code', 'order_drugs.*', 'dosage_name', 'route_name')
						->leftJoin('orders as b', 'b.order_id', '=', 'order_drugs.order_id')
						->leftJoin('drug_dosages as c', 'c.dosage_code', '=', 'order_drugs.dosage_code')
						->leftJoin('drug_routes as d', 'd.route_code', '=', 'order_drugs.route_code')
						->leftJoin('encounters as e', 'e.encounter_id', '=', 'b.encounter_id')
						->where('e.patient_id', $consultation->patient_id)
						->where('e.encounter_id', '<>', $encounter_id)
						->limit(10)
						->get();

			$table_row = '';
			$helper = new OrderHelper();
			foreach ($medications as $med) {
					//$html .= $med->order->product->drug->drug_generic_name." ";
					//$prescriptoin = $helper->getPrescription($med->order_id).'<br>';
					$drug_add = sprintf("<a class='btn btn-warning btn-xs' href='javascript:renewDrug(%s)'>Renew</a>", $med->order_id);
					$table_row .=sprintf(" 
							<tr>
							        <td width=150>%s</td>
							        <td width=%s>%s<br><small>%s</small></td>
							        <td>%s %s</td>
							        <td>%s %s</td>
							        <td>%s</td>
							        <td>%s</td>
							        <td>%s</td>
							        <td width='10'>%s</a></td>
							</tr>", 
							DojoUtility::dateLongFormat($med->order->consultation['created_at']),
							'30%',
							$med->order->product->drug?$med->order->product->drug->drug_generic_name:$med->order->product->product_name,
							$med->order->product->drug?$med->order->product->drug->trade_name:$med->order->product->product_name_other,
							$med->drug_strength,
							$med->unit_code,
							$med->drug_dosage,
							$med->dosage_name,
							$med->route_name,
							$med->frequency['frequency_name'],
							sprintf("%s", $med->drug_duration?$med->drug_duration.' '.$med->period['period_name']:'-'),
							$drug_add
					);
			}

			$html = '';
			if (empty($table_row)) {
				$html = "<br>";
			} else {
					$html = sprintf('
					<br>
					<h3>Drug History</h3>
					<div class="widget style1 gray-bg">
					<table class="table table-hover">
						 <thead>
							<tr> 
							<th>Date</th>
							<th>Drug</th>
							<th>Strength</th> 
							<th>Dosage</th> 
							<th>Route</th> 
							<th>Freqeuncy</th> 
							<th>Duration</th>
							<th></th>
							</tr>
						  </thead>
							%s
					</table>
					</div>
					<br>
				', $table_row);
			}

			return $html;
	}


	function medicationTable() {

			$consultation_id = Session::get('consultation_id');
			$consultation = Consultation::findOrFail($consultation_id);
			$encounter_id = $consultation->encounter_id;

			$medications = OrderDrug::orderBy('b.created_at')
						->select('order_drugs.unit_code as unit_code', 'order_drugs.*')
						->leftJoin('orders as b', 'b.order_id', '=', 'order_drugs.order_id')
						->leftJoin('drug_dosages as c', 'c.dosage_code', '=', 'order_drugs.dosage_code')
						->leftJoin('drug_routes as d', 'd.route_code', '=', 'order_drugs.route_code')
						->where('encounter_id', $encounter_id)
						->get();

			$table_row = "";
			$last_id = 0;
			foreach ($medications as $med) {
					$last_id = $med->order_id;
					$drug_remove = sprintf("<a tabindex='-1' class='pull-right btn btn-danger btn-sm' href='javascript:removeDrug(%s)'><span class='glyphicon glyphicon-trash'></span></a>", $med->order_id);
					$table_row .=sprintf(" 
							<tr height=50>
							        <td width=%s style='vertical-align:top'>%s<small>%s</small></td>
							        <td width=80 style='vertical-align:top'><input id='strength_%s' name='strength_%s' class='form-control input-sm small-font' type='text' value='%s'></td>
							        <td width=150 style='vertical-align:top'>%s</td>
							        <td width=20></td>
							        <td width=80 style='vertical-align:top'><input id='dosage_%s' name='dosage_%s' class='form-control input-sm small-font' type='text' value='%s'></td>
							        <td width=150 style='vertical-align:top'>%s</td>
							        <td width=20></td>
							        <td width=150 style='vertical-align:top'>%s</td>
							        <td width=20></td>
							        <td width=150 style='vertical-align:top'>%s</td>
							        <td width=20></td>
							        <td width=80 style='vertical-align:top'><input id='duration_%s' name='duration_%s' class='form-control input-sm small-font' type='text' value='%s'></td>
							        <td width=150 style='vertical-align:top'>%s</td>
							        <td width=20></td>
							        <td width='1' style='vertical-align:top'>%s</td>
							</tr>
							", 
							'30%',
							$med->order->product->drug?$med->order->product->drug->drug_generic_name:$med->order->product->product_name,
							$med->order->product->drug?$med->order->product->drug->trade_name:'<br>'.$med->order->product->product_name_other,
							$med->order_id,	$med->order_id, $med->drug_strength, 
							$this->getUnits($med->order->product_code, $med->order_id, $med->unit_code),
							$med->order_id,	$med->order_id,	$med->drug_dosage,
							$this->getDosages($med->order->product_code, $med->order_id, $med->dosage_code),
							$this->getRoutes($med->order->product_code, $med->order_id, $med->route_code),
							$this->getPrescriptionSelect($med->order->product_code, $med->order_id, $med->frequency_code),
							$med->order_id,
							$med->order_id,
							$med->drug_duration,
							$this->getPeriods($med->order->product_code, $med->order_id, $med->period_code),
							$drug_remove
					);
			}

			if (empty($table_row)) {
				$html = "";
			} else {
					$html = sprintf("
					<table>
						 <thead>
							<tr>
							<th>Drug</th>
							<th colspan=2>Strength</th>
							<th></th>
							<th colspan=2>Dosage</th> 
							<th></th> 
							<th>Route</th> 
							<th></th> 
							<th>Freqeuncy</th> 
							<th></th>
							<th colspan=2>Duration</th>
							<th></th>
							<th></th>
							</tr>
						  </thead>
						  <tr height=10></tr>
							%s
					</table>
					<input type='hidden' id='last_id' name='last_id' value='%s'>
				", $table_row, $last_id);

					/**
					$html = sprintf('
					<table class="table table-hover">
							%s
					</table>
				', $table_row);
				**/
			}

			if (count($medications)==0) $html = '';

			return $html;

	}

	function find(Request $request)
	{
			if (!empty($request->search)) {

				$fields = explode(' ', $request->search);

				/*
				$sql = "select drug_generic_name, trade_name, a.drug_code
						from drugs as a
						where (drug_generic_name like '%".$fields[0]."%'
						or trade_name like '%".$fields[0]."%') ";
				*/

				$sql = "select product_name as drug_generic_name, product_name_other as trade_name, product_code as drug_code
						from products as a
						where (product_name like '".$fields[0]."%'
						or product_name_other like '".$fields[0]."%') ";

				unset($fields[0]);

				if (count($fields)>0) {
						$sql .=" and (";
						foreach($fields as $key=>$field) {
								$sql .= "product_name like '%".$field."%'";
								if ($key<count($fields)) {
									$sql .= " and ";
								}
						}

						$sql .=")";
				}

				$sql .=" and category_code = 'drugs' limit 10";

				$data = DB::select($sql);

				$html = '';
				$table_row = '';
				$drug_dosage = "";
				$drug_route = "";

				foreach($data as $row) {
					$drug_name = $row->drug_generic_name;
					if (!empty($row->trade_name)) {
							$drug_name = sprintf('%s (%s)',$row->trade_name, $row->drug_generic_name);
					}


					$prescriptions = $this->getPrescription($row->drug_code);
					$route_dosage = null;
					$drug_prescription = "";
					$i = 0;
					foreach($prescriptions as $prescription) {
							if ($route_dosage != $prescription->drug_dosage.$prescription->dosage_code.$prescription->route_code) {
								$drug_prescription .= sprintf(' %s %s, %s (%s)',
										$prescription->drug_dosage,
										$prescription->dosage->dosage_name,
										$prescription->route->route_name,
										$prescription->route->route_code);

								$drug_dosage = sprintf("%s %s", $prescription->drug_dosage, $prescription->dosage->dosage_name);
								$drug_route = $prescription->route_code;
							}

							$route_dosage = $prescription->drug_dosage.$prescription->dosage_code.$prescription->route_code;
							$i++;
					}

					$drug_add = sprintf("<a class='btn btn-default btn-xs' href='javascript:addDrug(&quot;%s&quot;)' >+</a>", $row->drug_code);
					$table_row .=sprintf(" 
							<tr>
							        <td width=10>%s</td>
							        <td>%s</td>
							        <td>%s</td>
							</tr>", 
								$drug_add,
								$row->trade_name?:'-',
								$row->drug_generic_name
					);
				}

				$html = sprintf('
					<br>
					<table class="table table-hover">
							%s
					</table>
				', $table_row);


				if (count($data)==0) $html = '';
				return $html;

			}
	}

	function find_v1(Request $request)
	{
			if (!empty($request->search)) {

				$fields = explode(' ', $request->search);

				$sql = "select drug_generic_name, trade_name, a.drug_code
						from drugs as a
						where (drug_generic_name like '%".$fields[0]."%'
						or trade_name like '%".$fields[0]."%') ";

				unset($fields[0]);

				if (count($fields)>0) {
						$sql .=" and (";
						foreach($fields as $key=>$field) {
								$sql .= "drug_generic_name like '%".$field."%'";
								if ($key<count($fields)) {
									$sql .= " and ";
								}
						}

						$sql .=")";
				}

				$sql .=' limit 5';

				$data = DB::select($sql);

				$html = '';
				$table_row = '';
				$drug_dosage = "";
				$drug_route = "";

				foreach($data as $row) {
					$drug_name = $row->drug_generic_name;
					if (!empty($row->trade_name)) {
							$drug_name = sprintf('%s (%s)',$row->trade_name, $row->drug_generic_name);
					}


					$prescriptions = $this->getPrescription($row->drug_code);
					$route_dosage = null;
					$drug_prescription = "";
					$i = 0;
					foreach($prescriptions as $prescription) {
							if ($route_dosage != $prescription->drug_dosage.$prescription->dosage_code.$prescription->route_code) {
								$drug_prescription .= sprintf(' %s %s, %s (%s)',
										$prescription->drug_dosage,
										$prescription->dosage->dosage_name,
										$prescription->route->route_name,
										$prescription->route->route_code);

								$drug_dosage = sprintf("%s %s", $prescription->drug_dosage, $prescription->dosage->dosage_name);
								$drug_route = $prescription->route_code;
							}

							/*
							$drug_frequencies .= "<a class='btn btn-default btn-xs' href='javascript:addDrug(".$prescription->prescription_id.")' title='".$prescription->frequency->frequency_name."'>".$prescription->frequency->frequency_code."</a>";
							if ($i==5 && count($prescriptions)>6) {
									//$drug_frequencies .= "<br><br>";
							}
							if ($i+1<count($prescriptions)) {
									$drug_frequencies .= " ";
							}
							 */
							$route_dosage = $prescription->drug_dosage.$prescription->dosage_code.$prescription->route_code;
							$i++;
					}

					$drug_add = sprintf("<a class='btn btn-default btn-xs' href='javascript:addDrug(&quot;%s&quot;)' >+</a>", $row->drug_code);
					$table_row .=sprintf(" 
							<tr>
							        <td width=10>%s</td>
							        <td>%s</td>
							        <td>%s</td>
							</tr>", 
								$drug_add,
								$row->drug_generic_name, 
								$row->trade_name?:'-'
					);
				}

				$html = sprintf('
					<br>
					<table class="table table-hover">
							%s
					</table>
				', $table_row);


				if (count($data)==0) $html = '';
				return $html;

			}
	}

	function getPeriods($drug_code, $order_id, $period_code)
	{
			$periods =  Period::whereIn('period_code', array('day','week', 'month'))
							->select('period_name', 'period_code')
							->get();
			$html = '';
			$html .= sprintf("<option></option>");
			foreach($periods as $period) {
				$selected = "";
				if ($period->period_code == $period_code) $selected = "selected";
				$html .= sprintf("<option value='%s' %s>%s</option>", $period->period_code, $selected, $period->period_name);
			}

			$html = sprintf("<select id='period_%s' name='period_%s' class='form-control input-sm small-font' id='period_%s'>%s</select>", $order_id,  $order_id, $drug_code, $html);
			
			return $html;
	}

	function getUnits($drug_code, $order_id, $unit_code)
	{
			$units = Unit::where('unit_drug',1)->orderBy('unit_name')->select('unit_name', 'unit_code')->get();

			$html = '';
			$html .= sprintf("<option></option>");
			foreach($units as $unit) {
				$selected = "";
				if ($unit->unit_code == $unit_code) $selected = "selected";
				$html .= sprintf("<option value='%s' %s>%s</option>", $unit->unit_code, $selected, $unit->unit_name);
			}

			$html = sprintf("<select id='unit_%s' name='unit_%s' class='form-control input-sm small-font' >%s</select>", $order_id,  $order_id, $html);
			
			return $html;
	}

	function getRoutes($drug_code, $order_id, $route_code)
	{
			$routes = Route::orderBy('route_name')->select('route_name', 'route_code')->get();

			$html = '';
			$html .= sprintf("<option></option>");
			foreach($routes as $route) {
				$selected = "";
				if ($route->route_code == $route_code) $selected = "selected";
				$html .= sprintf("<option value='%s' %s>%s</option>", $route->route_code, $selected, $route->route_name);
			}

			$html = sprintf("<select id='route_%s' name='route_%s' class='form-control input-sm small-font' id='route_%s'>%s</select>", $order_id,  $order_id, $drug_code, $html);
			
			return $html;
	}

	function getDosages($drug_code, $order_id, $dosage_code)
	{
			$dosages = Dosage::orderBy('dosage_name')->select('dosage_name', 'dosage_code')->get();

			$html = '';
			$html .= sprintf("<option></option>");
			foreach($dosages as $dosage) {
				$selected = "";
				if ($dosage->dosage_code == $dosage_code) $selected = "selected";
				$html .= sprintf("<option value='%s' %s>%s</option>", $dosage->dosage_code, $selected, $dosage->dosage_name);
			}

			$html = sprintf("<select id='dosagecode_%s' name='dosagecode_%s' class='form-control input-sm small-font'>%s</select>", 
					$order_id,  $order_id, $html);
			
			return $html;
	}

	function getPrescriptionSelect($drug_code, $order_id, $frequency_code)
	{
			$prescriptions = DrugPrescription::where('drug_code', $drug_code)
					->orderBy('frequency_code')
					->pluck('frequency_code')
					->toArray();

			$frequencies = Frequency::orderBy('frequency_name')
					->select('frequency_code', 'frequency_name')
					->whereIn('frequency_code', $prescriptions)
					->get();

			if (count($frequencies)==0) {
					$frequencies = Frequency::orderBy('frequency_name')
							->select('frequency_code', 'frequency_name')
							->get();
			}

			$html = '';
			$html .= sprintf("<option></option>");
			foreach($frequencies as $frequency) {
				$selected = "";
				if ($frequency->frequency_code == $frequency_code) $selected = "selected";
				$html .= sprintf("<option value='%s' %s>%s</option>", $frequency->frequency_code, $selected, $frequency->frequency_name);
			}

			$html = sprintf("<select id='frequency_%s' name='frequency_%s' class='form-control input-sm small-font' id='prescription_%s'>%s</select>",$order_id, $order_id, $drug_code, $html);
			
			return $html;
	}

	function getPrescription($drug_code)
	{
			$data = DrugPrescription::where('drug_code', $drug_code)
					->orderBy('frequency_code')
					->get();
			
			return $data;
	}

	function fetch2(Request $request)
	{
			if (!empty($request->search)) {
				$fields = explode(' ', $request->search);
				Log::info($fields);
				$data = Product::select('product_name', 'product_code');

				foreach($fields as $field) {
					$data = $data->where('product_name', 'like', '%'.$field .'%');
				}

				$data = $data->where('category_code', 'drugs')
							->limit(10)
							->orderBy('product_name')
							->get();	

				$html = '';
				foreach($data as $row) {
					$html .= '<button type="button">+</button>'.$row->product_name.'<br>';	
				}
				return $html;

			}
	}

	public function create($product_code)
	{
			$consultation_id = Session::get('consultation_id');
			$order_drug = new OrderDrug();
			$consultation = Consultation::findOrFail($consultation_id);
			$product = DB::table('products')
						->select('product_name','product_code')
						->where('product_code','=',$product_code)->get();

			$order = new Order();
			$drug_prescription = DrugPrescription::where('drug_code','=',$product_code)->first();
			if (!empty($drug_prescription)) {
					$order_drug->drug_strength = $drug_prescription->drug_strength;
					$order_drug->unit_code = $drug_prescription->unit_code;
					$order_drug->drug_dosage = $drug_prescription->drug_dosage;
					$order_drug->dosage_code = $drug_prescription->dosage_code;
					$order_drug->route_code = $drug_prescription->route_code;
					$order_drug->frequency_code = $drug_prescription->frequency_code;
					$order_drug->drug_period = $drug_prescription->drug_period;
					$order_drug->period_code = $drug_prescription->period_code;
					$order->order_quantity_request = $drug_prescription->drug_total_unit;
					$order->order_description = $drug_prescription->drug_description;
			}
			return view('order_drugs.create', [
					'order_drug' => $order_drug,
					'unit' => Unit::where('unit_drug',1)->orderBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'frequencyValues' => Frequency::all()->lists('frequency_code', 'frequency_code')->prepend('',''),
					'period' => Period::whereIn('period_code', array('day','week', 'month'))->orderBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'consultation' => $consultation,
					'patient'=>$consultation->encounter->patient,
					'product' => $product[0],
					'tab'=>'order',
					'consultOption' => 'consultation',
					'order'=> $order, 
					]);
	}

	public function store(Request $request) 
	{
			$order_drug = new OrderDrug();
			$valid = $order_drug->validate($request->all(), $request->_method);
			if ($valid->passes()) {
					$product = Product::find($request->product_code);
					$order = new Order();
					$order->consultation_id = Session::get('consultation_id');
					$order->encounter_id = Session::get('encounter_id');
					$order->user_id = Auth::user()->id;
					$order->product_code = $request->product_code;
					$order->order_is_discharge = $request->order_is_discharge;
					$order->order_quantity_request = $request->order_quantity_request;
					$order->order_description = $request->order_description;
					$order->location_code = $product->location_code;
					$order->save();

					$order_drug = new OrderDrug($request->all());
					$order_drug->order_id = $order->order_id;
					$order_drug->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/orders');
			} else {
					return redirect('/order_drugs/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit(Request $request, $id) 
	{
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));
			$order_drug = OrderDrug::findOrFail($id);
			$product_code = $order_drug->order->product_code;
			$product = Product::find($product_code);

			$prescriptions = DrugPrescription::where('drug_code','=', $product_code)
					->leftjoin('drug_routes as c','c.route_code','=',  'drug_prescriptions.route_code')
					->leftjoin('drug_frequencies as d','d.frequency_code','=',  'drug_prescriptions.frequency_code')
					->leftjoin('drug_dosages as e', 'e.dosage_code','=', 'drug_prescriptions.dosage_code')
					->orderBy('frequency_name')
					->get();

			$frequencies = DrugPrescription::where('drug_code','=', $product_code)
					->leftjoin('drug_frequencies as d','d.frequency_code','=',  'drug_prescriptions.frequency_code')
					->orderBy('frequency_name')
					->lists('frequency_name', 'd.frequency_code')->prepend('','');

			if (count($frequencies)==1) {
					$frequencies = Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('','');
			}

			$indications = DrugDisease::where('drug_code','=', $product_code)->distinct()->get();
			$stock_helper = new StockHelper();
			$available = $stock_helper->getStockAvailable($product_code, $order_drug->order->store_code);

			return view('order_drugs.edit', [
					'order_drug'=>$order_drug,
					'unit' => Unit::where('unit_drug',1)->orderBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
					'frequencyValues' => Frequency::all(),
					'period' => Period::whereIn('period_code', array('day','week', 'month'))->orderBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'consultation' => $consultation,
					'frequency'=>$frequencies,
					'patient'=>$consultation->encounter->patient,
					'product' => $product,
					'tab'=>'order',
					'consultOption' => 'consultation',
					'order'=>$order_drug->order,
					'prescriptions'=>$prescriptions,
					'indications'=>$indications,
					'available'=>$available,
					'order_single'=>$request->order_single,
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock_helper = new StockHelper();
			$order_drug = OrderDrug::findOrFail($id);
			$order_drug->fill($request->input());

			$order = Order::find($order_drug->order_id);
			$product= Product::find($order->product_code);
			$order->fill($request->input());
			$order->order_is_discharge = $request->order_is_discharge ?: 0;
			$order->order_quantity_request = $request->order_quantity_request;
			$order->order_quantity_supply = $request->order_quantity_request;

			$local_store = $order->store;
			$available = $stock_helper->getStockAvailable($order->product_code, $order->store_code);

			if ($request->order_completed == 1) {
				// Set store to local when order completed
				$admission = EncounterHelper::getCurrentAdmission($order->encounter_id);

				if (!$admission) {
						$location_code = $request->cookie('queue_location');
						$order->location_code = $location_code;
				}

				if ($order->product->product_stocked==1) {
						$store_code = OrderHelper::getLocalStore($order->consultation->encounter, $admission);
						$order->store_code = $store_code;
				}
				$available = $stock_helper->getStockAvailable($order->product_code, $store_code);
				$local_store = Store::find($store_code);
			}

			if ($request->order_is_discharge==1) {
					$route = OrderRoute::where('encounter_code', $order->consultation->encounter->encounter_code)
							->where('category_code', $product->category_code)
							->first();
					if ($route) {
							$order->store_code = $route->store_code;
					}
			}

			$allocated = $stock_helper->getStockAllocated($product->product_code, $order->store_code);
			$on_hand = $stock_helper->getStockOnHand($product->product_code, $order->store_code);


			$valid=null;
			$valid = $order_drug->validate($request->all(), $request->_method);	

			/*
			if ($order->order_quantity_request>$on_hand-$allocated) {
					//$valid['order_quantity_request']='Insufficient quantity.'; 
					$valid->getMessageBag()->add('order_quantity_request', 'Insufficient quantity.');
					return redirect('/order_drugs/'.$id.'/edit')
							->withErrors($valid)
							->withInput()
							->with(['stock_count'=>$available, 'local_store'=>$local_store]);
			}
			 */

			if ($valid->passes()) {
					$order->save();
					$order_drug->save();
					OrderHelper::createDrugServings($order_drug);

					$order = Order::find($order_drug->order_id);
					$order->post_id=0;
					$order->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/orders');
			} else {
					return redirect('/order_drugs/'.$id.'/edit')
						->withErrors($valid);			
			}
	}
	

	public function delete($id)
	{
		$order_drug = OrderDrug::findOrFail($id);
		return view('order_drugs.destroy', [
			'order_drug'=>$order_drug
			]);

	}
	public function destroy($id)
	{	
			OrderDrug::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_drugs');
	}
	
	public function search(Request $request)
	{
			$order_drugs = DB::table('order_drugs')
					->where('drug_strength','like','%'.$request->search.'%')
					->orWhere('order_id', 'like','%'.$request->search.'%')
					->orderBy('drug_strength')
					->paginate($this->paginateValue);

			return view('order_drugs.index', [
					'order_drugs'=>$order_drugs,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$order_drugs = DB::table('order_drugs')
					->where('order_id','=',$id)
					->paginate($this->paginateValue);

			return view('order_drugs.index', [
					'order_drugs'=>$order_drugs
			]);
	}
}
