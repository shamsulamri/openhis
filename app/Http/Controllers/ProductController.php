<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use Log;
use DB;
use Session;
use App\ProductCategory as Category;
use App\UnitMeasure as Unit;
use App\QueueLocation as Location;
use App\Form;
use App\OrderForm;

class ProductController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$products = DB::table('products')
					->orderBy('product_name')
					->paginate($this->paginateValue);
			return view('products.index', [
					'products'=>$products
			]);
	}

	public function create()
	{
			$product = new Product();
			return view('products.create', [
					'product' => $product,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$product = new Product();
			$valid = $product->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product = new Product($request->all());
					$product->product_code = $request->product_code;
					$product->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/products/id/'.$product->product_code);
			} else {
					return redirect('/products/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$product = Product::findOrFail($id);
			return view('products.edit', [
					'product'=>$product,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product = Product::findOrFail($id);
			$product->fill($request->input());

			$product->product_active = $request->product_active ?: 0;
			$product->product_drop_shipment = $request->product_drop_shipment ?: 0;
			$product->product_stocked = $request->product_stocked ?: 0;
			$product->product_purchased = $request->product_purchased ?: 0;
			$product->product_sold = $request->product_sold ?: 0;
			$product->product_discontinued = $request->product_discontinued ?: 0;
			$product->product_bom = $request->product_bom ?: 0;
			$product->product_gst = $request->product_gst ?: 0;

			$valid = $product->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/products/id/'.$id);
			} else {
					return view('products.edit', [
							'product'=>$product,
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
		$product = Product::findOrFail($id);
		return view('products.destroy', [
			'product'=>$product
			]);

	}
	public function destroy($id)
	{	
			Product::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/products');
	}
	
	public function search(Request $request)
	{
			$products = DB::table('products')
					->where('product_name','like','%'.$request->search.'%')
					->orWhere('product_code', 'like','%'.$request->search.'%')
					->orderBy('product_name')
					->paginate($this->paginateValue);

			return view('products.index', [
					'products'=>$products,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$products = DB::table('products')
					->where('product_code','=',$id)
					->paginate($this->paginateValue);

			return view('products.index', [
					'products'=>$products
			]);
	}
}