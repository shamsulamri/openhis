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
use App\ProductStatus;
use App\Store;
use App\Stock;
use Carbon\Carbon;
use App\TaxCode;

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
			$product->order_form=1;
			$product->status_code='active';
			$product->form_code='generic';
			return view('products.create', [
					'product' => $product,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code'),
					'product_status' => ProductStatus::all()->sortBy('status_name')->lists('status_name', 'status_code'),
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
					'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'product_status' => ProductStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product = Product::findOrFail($id);
			$product->fill($request->input());

			$product->product_purchased = $request->product_purchased ?: 0;
			$product->product_sold = $request->product_sold ?: 0;
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
							'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
							'product_status' => ProductStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
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

	public function totalOnHand($product_code)
	{
			$stores = Store::all();
			$total=0;
			foreach ($stores as $store) {
					$total += $this->storeOnHand($product_code, $store->store_code);
			}
					
			$product = Product::find($product_code);
			$product->product_on_hand = $total;
			$product->save();		

			Log::info($total);
			return $total;
	}

	public function storeOnHand($product_code, $store_code) 
	{
			$stock_take = Stock::select('stock_date', 'stock_quantity')
							->where('move_code','=','take')
							->where('product_code','=',$product_code)
							->where('store_code','=',$store_code)
							->orderBy('stock_date', 'desc')
							->first();

			$stock_on_hand=0;
			if (!empty($stock_take)) {
					$stock_value = $stock_take->stock_quantity; 

					$take_date = Carbon::createFromFormat('d/m/Y H:i',$stock_take->stock_date);

					$stocks = Stock::where('stock_date','>=',$take_date)
									->where('product_code','=',$product_code)
									->where('store_code','=',$store_code)
									->where('move_code','<>', 'take')
									->sum('stock_quantity');
					$stock_on_hand = $stock_value + $stocks;
			} else {
					$stocks = Stock::where('product_code','=',$product_code)
									->where('store_code','=',$store_code)
									->sum('stock_quantity');
					$stock_on_hand=$stocks;
			}

			return $stock_on_hand;
	}
}
