<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderProduct;
use Log;
use DB;
use Session;
use App\Category;
use App\Unit;
use App\Location;
use App\Form;
use App\Consultation;
use App\Set;
use App\OrderSet;
use App\DojoUtility;

class OrderProductController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			return redirect('/order_product/drug');
			$order_products = DB::table('products')
					->orderBy('product_name')
					->paginate($this->paginateValue);
		 	$order_products = NULL; 	
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			return view('order_products.index', [
					'order_products'=>$order_products,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'order',
					'consultOption' => 'consultation',
					'sets' => Set::all()->sortBy('set_name')->lists('set_name', 'set_code')->prepend('',''),
					'set_value' => '',
					'search' => '',
			]);
	}

	public function create()
	{
			$order_product = new OrderProduct();
			return view('order_products.create', [
					'order_product' => $order_product,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$order_product = new OrderProduct();
			$valid = $order_product->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_product = new OrderProduct($request->all());
					$order_product->product_code = $request->product_code;
					$order_product->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/order_products/id/'.$order_product->product_code);
			} else {
					return redirect('/order_products/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_product = OrderProduct::findOrFail($id);
			return view('order_products.edit', [
					'order_product'=>$order_product,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_product = OrderProduct::findOrFail($id);
			$order_product->fill($request->input());

			$order_product->product_active = $request->product_active ?: 0;
			$order_product->product_drop_shipment = $request->product_drop_shipment ?: 0;
			$order_product->product_stocked = $request->product_stocked ?: 0;
			$order_product->product_purchased = $request->product_purchased ?: 0;
			$order_product->product_sold = $request->product_sold ?: 0;
			$order_product->product_discontinued = $request->product_discontinued ?: 0;
			$order_product->product_bom = $request->product_bom ?: 0;
			$order_product->product_gst = $request->product_gst ?: 0;

			$valid = $order_product->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_product->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_products/id/'.$id);
			} else {
					return view('order_products.edit', [
							'order_product'=>$order_product,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_product = OrderProduct::findOrFail($id);
		return view('order_products.destroy', [
			'order_product'=>$order_product
			]);

	}
	public function destroy($id)
	{	
			OrderProduct::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_products');
	}
	
	public function search(Request $request)
	{
			if (!empty($request->set_code)) {
				
				$orderSets = DB::table('order_sets')
							->select('product_code')
							->where('set_code','=',$request->set_code)
							->pluck('product_code');

				$order_products = DB::table('products')
					->whereIn('product_code', $orderSets)
					->orderBy('product_name')
					->paginate($this->paginateValue);
			} else {
				$order_products = DB::table('products')
					->where('product_name','like','%'.$request->search.'%')
					->where('product_sold','1')
					->orWhere('product_code', 'like','%'.$request->search.'%')
					->orderBy('product_name')
					->paginate($this->paginateValue);
			}
			$consultation_id = Session::get('consultation_id'); //$request->consultation_id;
			$consultation = Consultation::findOrFail($consultation_id);

			return view('order_products.index', [
					'order_products'=>$order_products,
					'search'=>$request->search,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'order',
					'consultOption' => 'consultation',
					'sets' => Set::all()->sortBy('set_name')->lists('set_name', 'set_code')->prepend('',''),
					'set_value' => $request->set_code,
					'page' => $request->page,
					]);
	}

	public function drugHistory(Request $request)
	{
			$consultation_id = Session::get('consultation_id'); //$request->consultation_id;
			$consultation = Consultation::findOrFail($consultation_id);

			$order_products = DB::table('orders as a')
					->select('a.order_id','a.product_code', 'product_name', 'b.created_at', 'drug_strength', 'unit_name', 'drug_dosage', 'dosage_name', 'route_name', 'frequency_name','drug_duration', 'period_name')
					->leftJoin('encounters as b', 'a.encounter_id','=', 'b.encounter_id')
					->leftJoin('products as c', 'c.product_code', '=', 'a.product_code')
					->leftJoin('order_drugs as d', 'd.order_id', '=', 'a.order_id')
					->leftJoin('drug_dosages as e', 'e.dosage_code', '=', 'd.dosage_code')
					->leftJoin('drug_routes as f', 'f.route_code', '=', 'd.route_code')
					->leftJoin('drug_frequencies as g', 'g.frequency_code', '=', 'd.frequency_code')
					->leftJoin('ref_periods as h', 'h.period_code', '=', 'd.period_code')
					->leftJoin('ref_unit_measures as i', 'i.unit_code', '=', 'd.unit_code')
					->where('patient_id',$consultation->encounter->patient->patient_id)
					->where('category_code','drugs')
					->orderBy('order_id','desc')
					->paginate($this->paginateValue);
					

			return view('order_products.index', [
					'order_products'=>$order_products,
					'search'=>$request->search,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'drug',
					'consultOption' => 'consultation',
					'sets' => Set::all()->sortBy('set_name')->lists('set_name', 'set_code')->prepend('',''),
					'set_value' => $request->set_code,
					'page' => $request->page,
					'dojo' => new DojoUtility(),
					]);
	}

	public function searchById($id)
	{
			$order_products = DB::table('products')
					->where('product_code','=',$id)
					->paginate($this->paginateValue);

			return view('order_products.index', [
					'order_products'=>$order_products
			]);
	}
}
