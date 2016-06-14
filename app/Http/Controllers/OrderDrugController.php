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
use App\Period;
use App\Order;
use App\Consultation;
use App\Product;
use Auth;
use App\DrugPrescription;

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
					$order_drug->drug_prn = $drug_prescription->drug_prn;
					$order_drug->drug_meal = $drug_prescription->drug_meal;
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
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
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
					$order->order_sale_price = $product->product_sale_price;
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

	public function edit($id) 
	{
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));
			$order_drug = OrderDrug::findOrFail($id);
			$product_code = $order_drug->order->product_code;
			$product = DB::table('products')
						->select('product_name','product_code')
						->where('product_code','=',$product_code)->get();

			return view('order_drugs.edit', [
					'order_drug'=>$order_drug,
					'unit' => Unit::where('unit_drug',1)->orderBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'frequencyValues' => Frequency::all(),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'consultation' => $consultation,
					'patient'=>$consultation->encounter->patient,
					'product' => $product[0],
					'tab'=>'order',
					'consultOption' => 'consultation',
					'order'=>$order_drug->order,
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_drug = OrderDrug::findOrFail($id);
			$order_drug->fill($request->input());
			$order_drug->drug_prn = $request->drug_prn ?: 0;
			$order_drug->drug_meal = $request->drug_meal ?: 0;

			$order = Order::find($order_drug->order_id);
			$product= Product::find($order->product_code);
			$order->fill($request->input());
			$order->order_is_discharge = $request->order_is_discharge ?: 0;
			$order->order_quantity_supply = $request->order_quantity_request;
			$order->order_total = $product->product_sale_price*$order->order_quantity_request;
			$order->save();
			
			$valid = $order_drug->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_drug->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/orders');
			} else {
					return view('order_drugs.edit', [
							'order_drug'=>$order_drug,
							'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
							'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
							'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
							'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
							'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					])->withErrors($valid);			
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
