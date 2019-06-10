<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ConsultationProcedure;
use Log;
use DB;
use Session;
use App\Consultation;
use App\Order;
use App\Product;
use App\OrderHelper;
use App\OrderMultiple;

class ConsultationProcedureController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$consultation = Consultation::find(Session::get('consultation_id'));

			$consultation_procedures = DB::table('consultation_procedures as a')
					->select('id', 'a.created_at', 'procedure_description','procedure_is_principal')
					->leftjoin('consultations as b','b.consultation_id', '=', 'a.consultation_id')
					->leftjoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftjoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
					->where('c.encounter_id','=',$consultation->encounter_id)
					->orderBy('c.encounter_id', 'desc')
					->orderBy('procedure_is_principal', 'desc')
					->paginate($this->paginateValue);

					//->where('d.patient_id', $consultation->encounter->patient->patient_id)

			/*
			if ($consultation_procedures->count()==0) {
					return $this->create();
			} else {
					return view('consultation_procedures.index', [
							'consultation_procedures'=>$consultation_procedures,
							'consultation' => $consultation,
							'patient'=>$consultation->encounter->patient,
							'tab'=>'procedure',
							'consultOption' => 'consultation',
					]);
			}
			 */

			return view('consultation_procedures.index', [
					'consultation_procedures'=>$consultation_procedures,
					'consultation' => $consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'procedure',
					'consultOption' => 'consultation',
			]);
	}

	public function create()
	{
			$consultation_id = Session::get('consultation_id');
			$consultation_procedure = new ConsultationProcedure();
			if (empty($consultation_id)==false) {
					$consultation_procedure->consultation_id = $consultation_id;
			}

			$consultation = Consultation::findOrFail($consultation_id);

			$orders = Order::where('encounter_id', $consultation->encounter_id)
					->leftJoin('products as b', 'b.product_code', '=', 'orders.product_code')
					->where('category_code', 'fee_procedure')
					->get();

			return view('consultation_procedures.create', [
					'consultation_procedure' => $consultation_procedure,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'procedure',
					'consultOption' => 'consultation',
					'orders'=>$orders,
					]);
	}

	public function store_ajax(Request $request)
	{
			if ($request->ajax()) {

				$procedure = new ConsultationProcedure();
				$procedure->consultation_id = $request->id;
				$procedure->procedure_description = $request->procedure_description;
				$procedure->procedure_is_principal = $this->hasPrincipal();
				$procedure->save();

				return $this->generateHTML();

			}
	}

	private function hasPrincipal()
	{
				$consultation = Consultation::findOrFail(Session::get('consultation_id'));

				$principal = DB::table('consultation_procedures as a')
					->select('id', 'a.created_at', 'procedure_description','procedure_is_principal')
					->leftjoin('consultations as b','b.consultation_id', '=', 'a.consultation_id')
					->where('encounter_id','=',$consultation->encounter_id)
					->where('procedure_is_principal','=',1)
					->count();

				if ($principal) {
						return 0;
				} else {
						return 1;
				}

	}	

	public function store(Request $request) 
	{
			$consultation_procedure = new ConsultationProcedure();
			$valid = $consultation_procedure->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$consultation_procedure = new ConsultationProcedure($request->all());
					$consultation_procedure->id = $request->id;
					if ($consultation_procedure->procedure_is_principal) {
							$this->changeAllProcedureToSecondary();
					}
					$consultation_procedure->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/consultation_procedures/');
			} else {
					return redirect('/consultation_procedures/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$consultation_procedure = ConsultationProcedure::findOrFail($id);
			$consultation = Consultation::findOrFail($consultation_procedure->consultation_id);

			return view('consultation_procedures.edit', [
					'consultation_procedure'=>$consultation_procedure,
					'consultation'=>$consultation,	
					'patient'=>$consultation->encounter->patient,
					'tab'=>'procedure',
					'consultOption' => 'consultation',
					]);
	}

	public function update(Request $request, $id) 
	{
			$consultation_procedure = ConsultationProcedure::findOrFail($id);
			$consultation_procedure->fill($request->input());

			$consultation_procedure->procedure_is_principal = $request->procedure_is_principal ?: 0;

			$valid = $consultation_procedure->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					if ($consultation_procedure->procedure_is_principal) {
							$this->changeAllProcedureToSecondary();
					}
					$consultation_procedure->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultation_procedures/');
			} else {
					return view('consultation_procedures.edit', [
							'consultation_procedure'=>$consultation_procedure,
							'consultation'=>$consultation,
							'tab'=>'procedure',	
							])
							->withErrors($valid);			
			}
	}

	public function changeAllProcedureToSecondary() 
	{
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));
			$procedures = DB::table('consultation_procedures as a')
					->select('id')
					->leftjoin('consultations as b','b.consultation_id', '=', 'a.consultation_id')
					->where('encounter_id','=',$consultation->encounter_id)
					->get();

			foreach ($procedures as $procedure) {
					DB::table('consultation_procedures')
							->where('id',$procedure->id)
							->update(['procedure_is_principal'=>'0']);
			}

	}
	
	public function delete($id)
	{
		$consultation = Consultation::findOrFail(Session::get('consultation_id'));
		$consultation_procedure = ConsultationProcedure::findOrFail($id);
		return view('consultation_procedures.destroy', [
					'consultation_procedure'=>$consultation_procedure,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'procedure',
					'consultOption' => 'consultation',
			]);

	}
	public function destroy($id)
	{	
			ConsultationProcedure::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultation_procedures/');
	}
	
	public function search(Request $request)
	{
			$consultation_procedures = DB::table('consultation_procedures')
					->where('procedure_description','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
					->orderBy('procedure_description')
					->paginate($this->paginateValue);

			return view('consultation_procedures.index', [
					'consultation_procedures'=>$consultation_procedures,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$consultation_procedures = DB::table('consultation_procedures')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('consultation_procedures.index', [
					'consultation_procedures'=>$consultation_procedures
			]);
	}

	public function getProcedures(Request $request)
	{
			if ($request->ajax()) {
					return $this->generateHTML();
			}

	}

	private function generateHTML()
	{
				$consultation = Consultation::find(Session::get('consultation_id'));
	
				$procedures = DB::table('consultation_procedures as a')
					->select('id', 'a.created_at', 'procedure_description','procedure_is_principal')
					->leftjoin('consultations as b','b.consultation_id', '=', 'a.consultation_id')
					->where('encounter_id','=',$consultation->encounter_id)
					->orderBy('procedure_is_principal', 'desc')
					->orderBy('a.created_at', 'desc')
					->paginate($this->paginateValue);

				$html='<table class="table table-hover">';
				$principal='';

				foreach($procedures as $procedure) {

						if ($procedure->procedure_is_principal==1) {
								$principal = "
										<div class='label label-primary' title='Princiapl Procedure'>
										1°	
										</div>
								";
						} else {
								$principal = "
										<div class='label label-default' title='Secondary Procedure'>
										2°	
										</div>
								";
						}

						$html = $html."
							<tr>
									<td width='5%'>".$principal."</td>
									<td>".$procedure->procedure_description."</td>
									<td class='col-xs-3'>
									</td>
									<td align='right'>
										<a class='btn btn-danger btn-xs' href='/consultation_procedures/delete/". $procedure->id."'>Delete</a>
									</td>
							</tr>
						";
				}
				$html = $html."</table>";
				return $html;

	}


	function find(Request $request)
	{
			if (!empty($request->search)) {

				$fields = explode(' ', $request->search);

				$sql = "select product_name, product_code 
						from products as a
						where (product_name like '%".$fields[0]."%'";

				unset($fields[0]);

				if (count($fields)>0) {
						$sql .=" and ";
						foreach($fields as $key=>$field) {
								$sql .= "product_name like '%".$field."%'";
								if ($key<count($fields)) {
									$sql .= " and ";
								}
						}

				}

				$sql .=") and category_code = 'fee_procedure' limit 10";

				$data = DB::select($sql);

				$html = '';
				$table_row = '';

				foreach($data as $row) {
					$add_link = sprintf("<a class='btn btn-default btn-xs' href='javascript:addItem(&quot;%s&quot;)' >+</a>", $row->product_code);
					$table_row .=sprintf(" 
							<tr>
							        <td width=10>%s</td>
							        <td>%s</td>
							        <td>%s</td>
							</tr>", 
								$add_link,
								$row->product_name, 
								$row->product_code?:'-'
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

	function addProcedure(Request $request)
	{
			$product = Product::find($request->product_code);
			if ($product) {
					OrderHelper::orderItem($product, $request->cookie('ward'));
			}
			return $this->listProcedure();
	}

	function listProcedure() 
	{
			Log::info("List procedure..........");
			$consultation_id = Session::get('consultation_id');
			$consultation = Consultation::findOrFail($consultation_id);
			$encounter_id = $consultation->encounter_id;

			$procedures = Order::orderBy('order_id')
					->leftJoin('products as c', 'c.product_code', '=', 'orders.product_code')
					->where('category_code', 'fee_procedure')
					->where('orders.encounter_id','=',$encounter_id)
					->get();


			$html = '';
			$table_row = '';
			foreach($procedures as $procedure) {
				$item_remove = '';
				$item_price = '';
				$item_discount = '';
				if ($procedure->user_id == $consultation->user_id) {
						$item_remove = sprintf("<a tabindex='-1' class='pull-right btn btn-danger btn-sm' href='javascript:removeProcedure(%s)'><span class='glyphicon glyphicon-trash'></span></a>", $procedure->order_id);
				
						$item_price = sprintf("<input id='price_%s' name='price_%s' class='form-control input-sm small-font' type='text' value='%s'>",
							$procedure->order_id,
							$procedure->order_id,
							$procedure->order_unit_price
						);

						$item_discount = sprintf("<input id='discount_%s' name='discount_%s' class='form-control input-sm small-font' type='text' value='%s'>",
							$procedure->order_id,
							$procedure->order_id,
							$procedure->order_discount
						);
				} else {
						$item_price = sprintf("<label id='price_%s' name='price_%s' class='form-control input-sm small-font'>%s</label>",
							$procedure->order_id,
							$procedure->order_id,
							$procedure->order_unit_price
						);

						$item_discount = sprintf("<label id='discount_%s' name='discount_%s' class='form-control input-sm small-font'>%s</label>",
							$procedure->order_id,
							$procedure->order_id,
							$procedure->order_discount
						);
				}

				$table_row .=sprintf(" 
							<tr height=35>
							        <td width='100' style='vertical-align:top'>%s</td>
							        <td>%s</td>
							        <td width=100>%s</td>
							        <td width=5></td>
							        <td width=100>%s</td>
							        <td width=100>%s</td>
							</tr>
							<tr height=10></tr>
					", 
					$procedure->product_code,
					$procedure->product_name.'<br><small>'.$procedure->user->name.'</small>',
					$item_price,
					$item_discount,
					$item_remove
				);
			}

			if (empty($table_row)) {
				$html = "";
			} else {
					$html = sprintf("
					<table width='%s'>
						 <thead>
							<tr height=35>
									<th>Code</th>
									<th>Procedure</th>
									<th>Price (RM)</th>
									<th></th>
									<th>Discount (%s)</th>
							</tr>
						  </thead>
							%s
					</table>
				", '100%', '%', $table_row);
			}

			return $html;
	}

	public function removeProcedure(Request $request)
	{
			$order_id = $request->order_id;
			$order = Order::find($order_id);
			OrderMultiple::where('order_id', $order_id)->delete();
			Order::find($order_id)->delete();
					
			return $this->listProcedure();
	}

	public function updateProcedure(Request $request)
	{
			$order_id = $request->order_id;
			$order = Order::where('order_id', $order_id)->first();

			if ($order) {
					$order->order_unit_price = $request->price;
					$order->order_discount = $request->discount;
					$order->save();
					Log::info($order);
			}

			Log::info("Update procedure......");
	}

}
