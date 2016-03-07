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

class OrderProductController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($id)
	{
			$order_products = DB::table('products')
					->orderBy('product_name')
					->paginate($this->paginateValue);
			
			$consultation = Consultation::findOrFail($id);

			return view('order_products.index', [
					'order_products'=>$order_products,
					'consultation'=>$consultation,
					'tab'=>'order',
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
			$order_products = DB::table('products')
					->where('product_name','like','%'.$request->search.'%')
					->orWhere('product_code', 'like','%'.$request->search.'%')
					->orderBy('product_name')
					->paginate($this->paginateValue);

			$consultation_id = $request->consultation_id;
			$consultation = Consultation::findOrFail($consultation_id);

			return view('order_products.index', [
					'order_products'=>$order_products,
					'search'=>$request->search,
					'consultation'=>$consultation,
					'tab'=>'order',
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
