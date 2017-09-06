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
use App\ProductStatus as Status;
use App\UnitMeasure as Unit;
use App\QueueLocation as Location;
use App\Form;
use App\OrderForm;
use App\ProductStatus;
use App\Store;
use App\Stock;
use App\StockStore;
use Carbon\Carbon;
use App\TaxCode;
use Gate;
use App\Order;
use Auth;
use App\ProductAuthorization;
use App\Ward;
use App\StoreAuthorization;
use App\ProductCategory;
use App\GeneralLedger;
use App\StockHelper;

class ProductController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$stock_helper = new StockHelper();

			$loan=False;
			if (Auth::user()->authorization->author_id==7) {
					$loan=True;
			}

			$products = Product::orderBy('product_name')
					->leftjoin('product_categories as b', 'b.category_code','=', 'products.category_code');

			$category_codes = Auth::user()->categoryCodes();
			if (count($category_codes)>0) {
					$products = $products->whereIn('products.category_code',$category_codes);
			}

			$products = $products->orderBy('products.created_at','desc')
							->paginate($this->paginateValue);

			return view('products.index', [
					'products'=>$products,
					'loan'=>$loan,
					'categories'=>Auth::user()->categoryList(),
					'category_code'=>null,
			]);
	}

	public function create()
	{
			$product = new Product();
			$product->order_form=1;
			$product->status_code='active';
			$product->form_code='generic';

			$categories = Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('','');

			$product_authorization = ProductAuthorization::select('category_code')->where('author_id', Auth::user()->author_id);
			/*
			if ($product_authorization) {
					$categories = Category::whereIn('category_code',$product_authorization->pluck('category_code'))->orderBy('category_name')->lists('category_name', 'category_code')->prepend('','');
			}
			 */

			return view('products.create', [
					'product' => $product,
					'category' => $categories,
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code'),
					'product_status' => ProductStatus::all()->sortBy('status_name')->lists('status_name', 'status_code'),
					]);
	}

	public function option($id)
	{
			$product = Product::find($id); 
			return view('products.option', [
					'product' => $product,
			]);	
	}

	public function show(Request $request, $id)
	{
			$return_id = $request->id;
			$product = Product::find($id); 
			$viewpage ="products.show";
			if ($request->detail) {
				$viewpage ="products.show_detail";
			}

			return view($viewpage, [
						'product' => $product,
						'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
						'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
						'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
						'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
						'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
						'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code'),
						'status' => ProductStatus::all()->sortBy('status_name')->lists('status_name', 'status_code'),
						'return_id' => $return_id,
						'reason' => $request->reason,
						'product_status' => ProductStatus::all()->sortBy('status_name')->lists('status_name', 'status_code'),
						'stock_helper'=>new StockHelper(),
						'store'=>Auth::user()->storeList()->prepend('',''),
						'store_code'=>null,
				]);	
	}

	public function json($id)
	{
		$product = Product::find($id);
		return $product;
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

			$store_code = 'main';
			if (Auth::user()->authorization->store_code) {
				$store_code = Auth::user()->authorization->store_code;
			}

			$product_authorization = ProductAuthorization::select('category_code')->where('author_id', Auth::user()->author_id);
			return view('products.edit', [
					'product'=>$product,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'product_status' => ProductStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'store_code'=>$store_code,
					'categories'=>Auth::user()->categoryList(),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product = Product::findOrFail($id);
			$product->fill($request->input());

			$product->product_purchased = $request->product_purchased ?: 0;
			$product->product_sold = $request->product_sold ?: 0;
			$product->product_bom = $request->product_bom ?: 0;
			$product->product_stocked = $request->product_stocked ?: 0;
			$product->product_dismantle_material = $request->product_dismantle_material ?: 0;
			$product->product_drop_charge = $request->product_drop_charge ?: 0;
			$product->product_track_batch = $request->product_track_batch ?: 0;

			$valid = $product->validate($request->all(), $request->_method);	


			if ($valid->passes()) {
					$product->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/products/'.$id.'/edit');
			} else {
					return view('products.edit', [
							'product'=>$product,
							'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
							'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
							'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
							'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
							'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
							'product_status' => ProductStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
							'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
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
			$loan=False;
			if (Auth::user()->authorization->author_id==7) {
					$loan=True;
			}

			$products = $this->search_query($request, TRUE);

			return view('products.index', [
					'products'=>$products,
					'search'=>$request->search,
					'loan'=>$loan,
					'store'=>Auth::user()->storeList()->prepend('All Store','all')->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'store_code'=>$request->store,
					'stock_helper'=> new StockHelper(),
					'category_code'=>$request->category_code,
					]);
	}

	public function onHandEnquiry(Request $request)
	{
			$loan=False;
			if (Auth::user()->authorization->author_id==7) {
					$loan=True;
			}

			if (empty($request->store)) {
			//		$request->store = Auth::user()->defaultStore();
			}
			$products = $this->search_query($request, FALSE);

			return view('products.on_hand', [
					'products'=>$products,
					'search'=>$request->search,
					'loan'=>$loan,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'store_code'=>$request->store,
					'stock_helper'=> new StockHelper(),
					'category_code'=>$request->category_code,
					]);
	}

	public function search_query($request, $is_product_list=FALSE, $is_reorder=FALSE)
	{
			/*** Base ***/
			$products = Product::orderBy('product_name')
					->leftjoin('product_categories as c', 'c.category_code','=', 'products.category_code');

			/*** Not product list ***/
			if (!$is_product_list) {
					if (empty($request->store)) {
							$products = StockStore::leftjoin('products', 'products.product_code', '=', 'stock_stores.product_code')
									->whereIn('stock_stores.store_code', Auth::user()->storeCodes());
					} else {
							$products = StockStore::leftjoin('products', 'products.product_code', '=', 'stock_stores.product_code')
									->where('stock_stores.store_code',$request->store);
					}

					$products = $products->leftJoin('stock_limits as d', function($query) use ($request) {
							$query->on('d.product_code','=', 'products.product_code')
								->on('d.store_code','=', 'stock_stores.store_code');	
					});

					$products = $products->select('product_name', 'stock_stores.product_code', 'stock_stores.store_code', 'product_on_hand', 'product_stocked', 'limit_max', 'limit_min', 'product_average_cost');
			}


			/*** Reorder ***/
			if ($is_reorder) {
					$products = $products->where('stock_stores.stock_quantity','<', DB::raw('d.limit_min'))
										->leftjoin('stores as f', 'f.store_code', '=', 'stock_stores.store_code')
										->orderBy('store_name');
			}

			/*** Category ***/
			$category_codes = Auth::user()->categoryCodes();
			if (!empty($request->category_code)) {
					$products = $products->where('products.category_code','=', $request->category_code);
			} else {
					if (count($category_codes)>0) {
							$products = $products->whereIn('products.category_code',$category_codes);
					}
			}

			$products = $products->leftjoin('ref_unit_measures as e', 'e.unit_code', '=', 'products.unit_code');

			/*** Seach Param ***/
			if (!empty($request->search)) {
					$products = $products->where(function ($query) use ($request) {
								$query->where('product_name','like','%'.$request->search.'%')
								->orWhere('product_name_other','like','%'.$request->search.'%')
								->orWhere('products.product_code','like','%'.$request->search.'%');
					});
			}

			//dd($products->toSql());
			$products = $products->paginate($this->paginateValue);

			return $products;
	}

	public function searchById(Request $request, $id)
	{
			$products = DB::table('products')
					->where('product_code','=',$id)
					->paginate($this->paginateValue);

			$product_authorization = ProductAuthorization::select('category_code')->where('author_id', Auth::user()->author_id);
			if (!$product_authorization->get()->isEmpty()) {
					$products = $products->whereIn('products.category_code',$product_authorization->pluck('category_code'));
			}
			return view('products.index', [
					'products'=>$products,
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'store_code'=>null,
					'stock_helper'=>new StockHelper(),
					'default_store'=>Auth::user()->defaultStore(),
					'categories'=>Auth::user()->categoryList(),
					'category_code'=>$request->category_code,
			]);
	}


	public function updateTotalOnHand($product_code) 
	{
		$stock_helper = new StockHelper();
		$stock_helper->updateAllStockOnHand($product_code);
	}
	/**
	public function updateTotalOnHand($product_code)
	{
			StockStore::where('product_code','=', $product_code)->delete();
			$stores = Store::all();
			$total=0;
			foreach ($stores as $store) {
					$total += $this->storeOnHand($product_code, $store->store_code);
			}
					
			$product = Product::find($product_code);
			$product->product_on_hand = $total;
			$product->save();		

			return $total;
	}

	public function storeOnHand($product_code, $store_code) 
	{

			$stock_take = Stock::select('stock_datetime', 'stock_quantity')
							->where('move_code','=','take')
							->where('product_code','=',$product_code)
							->where('store_code','=',$store_code)
							->orderBy('stock_datetime', 'desc')
							->orderBy('stock_id', 'desc')
							->first();

			$stock_on_hand=0;

			if (!empty($stock_take)) {
					$stock_value = $stock_take->stock_quantity; 

					$take_date = $stock_take->stock_datetime; 

					$stocks = Stock::where('stock_datetime','>=',$take_date)
									->where('product_code','=',$product_code)
									->where('store_code','=',$store_code)
									->where('move_code','<>', 'take')
									->sum('stock_quantity');

					$used = Order::where('product_code','=', $product_code)
								->where('created_at','>', $take_date)
								->where('order_completed','=',1)
								->sum('order_quantity_supply');

					$stock_on_hand = $stock_value + $stocks - $used;
			} else {
					$stocks = Stock::where('product_code','=',$product_code)
									->where('store_code','=',$store_code)
									->sum('stock_quantity');

					$used = Order::where('product_code','=', $product_code)
								->where('order_completed','=',1)
								->where('store_code', '=', $store_code)
								->sum('order_quantity_supply');

					$stock_on_hand=$stocks - $used;
			}

			if ($stock_on_hand>0) {
					$stock_store = new StockStore();
					$stock_store->product_code = $product_code;
					$stock_store->store_code = $store_code;
					$stock_store->stock_quantity = $stock_on_hand;
					$stock_store->save();
			}


			Log::info($store_code.':'.$stock_on_hand);
			return $stock_on_hand;
	}

	**/

	public function enquiry(Request $request)
	{
		$stock_helper = new StockHelper();
		$product = null;
		$store_code = null;

		if (!empty($request->search)) {
			$product = Product::find($request->search);
		}

		if (!empty($request->store_code)) $store_code = $request->store_code;

		return view('products.enquiry', [
				'product'=>$product,
				'stock_helper'=>$stock_helper,
				'store'=>Auth::user()->storeList()->prepend('',''),
				'store_code'=>$store_code,
				'search'=>$request->search,
		]);
	}

	public function reorder(Request $request)
	{
			$loan=False;
			if (Auth::user()->authorization->author_id==7) {
					$loan=True;
			}

			if (empty($request->store)) {
			//		$request->store = Auth::user()->defaultStore();
			} 

			$products = $this->search_query($request, FALSE, TRUE);

			return view('products.reorder', [
					'products'=>$products,
					'search'=>$request->search,
					'loan'=>$loan,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'store_code'=>$request->store,
					'stock_helper'=> new StockHelper(),
					'category_code'=>$request->category_code,
			]);
	}

}
