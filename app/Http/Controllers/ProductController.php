<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

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
use App\Inventory;
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
use App\DojoUtility;
use App\ProductCharge;
use App\ProductUom;
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

			$store = Store::find(Auth::user()->defaultStore($request))?:null;

			return view('products.index', [
					'products'=>$products,
					'loan'=>$loan,
					'categories'=>Auth::user()->categoryList(),
					'category_code'=>null,
					'helper'=>new StockHelper(),
					'store'=>$store,
			]);
	}

	public function create()
	{
			$product = new Product();
			$product->order_form=1;
			$product->status_code='active';
			$product->form_code='generic';
			$product->tax_code= 'ES';
			$product->unit_code= 'unit';

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
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code'),
					'product_status' => ProductStatus::all()->sortBy('status_name')->lists('status_name', 'status_code'),
					'charges' => ProductCharge::all()->sortBy('charge_name')->lists('charge_name', 'charge_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
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

					$product_uom = new ProductUom();
					$product_uom->product_code = $product->product_code;
					$product_uom->unit_code = 'unit';
					$product_uom->uom_rate = 1;
					$product_uom->save();

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
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					'order_form' => OrderForm::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'product_status' => ProductStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'store_code'=>$store_code,
					'categories'=>Auth::user()->categoryList(),
					'charges' => ProductCharge::all()->sortBy('charge_name')->lists('charge_name', 'charge_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product = Product::findOrFail($id);
			$product->fill($request->input());

			$product->product_stocked = $request->product_stocked ?: 0;
			$product->product_edit_price = $request->product_edit_price ?: 0;
			$product->product_drop_charge = $request->product_drop_charge ?: 0;
			$product->product_unit_charge = $request->product_unit_charge ?: 0;
			$product->product_local_store = $request->product_local_store ?: 0;
			$product->product_non_claimable = $request->product_non_claimable ?: 0;
			$product->product_duration_use = $request->product_duration_use ?: 0;

			$valid = $product->validate($request->all(), $request->_method);	


			if ($valid->passes()) {
					$product->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/products/'.$id.'/edit');
			} else {
					return redirect('/products/'.$id.'/edit')
							->withErrors($valid)
							->withInput();
			}
	}
	
	public function delete($id)
	{
		$product = Product::find($id);
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

			$store = Store::find(Auth::user()->defaultStore($request));

			return view('products.index', [
					'products'=>$products,
					'search'=>$request->search,
					'loan'=>$loan,
					'store'=>Auth::user()->storeList()->prepend('All Store','all')->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'store'=>$store,
					'helper'=>new StockHelper(),
					'category_code'=>$request->category_code,
					]);
	}

	public function search_query($request, $is_product_list=FALSE, $is_reorder=FALSE)
	{
			/*** Base ***/
			$products = Product::orderBy('product_name')
					->leftjoin('product_categories as c', 'c.category_code','=', 'products.category_code');

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

			$products = $products->withTrashed();

			/*** Seach Param ***/
			if (!empty($request->search)) {
					$products = $products->where(function ($query) use ($request) {
							$search_param = trim($request->search, " ");
								$query->where('product_name','like','%'.$search_param.'%')
								->orWhere('product_name_other','like','%'.$search_param.'%')
								->orWhere('products.product_code','like','%'.$search_param.'%');
					});
			}

			//dd($products->toSql());
			$products = $products->paginate($this->paginateValue);

			return $products;
	}

	public function searchById(Request $request, $id)
	{
			$products = Product::where('product_code','=',$id)
					->paginate($this->paginateValue);

			$product_authorization = ProductAuthorization::select('category_code')->where('author_id', Auth::user()->author_id);
			if (!$product_authorization->get()->isEmpty()) {
					$products = $products->whereIn('products.category_code',$product_authorization->pluck('category_code'));
			}

			$store = Store::find(Auth::user()->defaultStore($request));

			return view('products.index', [
					'products'=>$products,
					'store'=>$store,
					'categories'=>Auth::user()->categoryList(),
					'category_code'=>$request->category_code,
					'helper'=>new StockHelper(),
			]);
	}


	public function updateTotalOnHand($product_code) 
	{
		$stock_helper = new StockHelper();
		$stock_helper->updateAllStockOnHand($product_code);
	}

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
			$sql = "
				select product_name, a.product_code, store_name, a.stock_quantity, limit_min, limit_max, on_purchase, on_transfer, in_transfer, unit_shortname
				from stock_stores as a 
				left join products as b on (a.product_code = b.product_code) 
				left join stores as c on (c.store_code = a.store_code) 
				left join stock_limits as d on (d.product_code = b.product_code and d.store_code = a.store_code)
				left join (
						select sum(line_quantity) as on_purchase, product_code
						from purchase_order_lines as a
						left join purchase_orders as b on (a.purchase_id = b.purchase_id)
						where purchase_posted=1 
						and purchase_received=0
						group by product_code
				) as e on (e.product_code = a.product_code)
				left join (
						select sum(line_quantity) as on_transfer, product_code, store_code
						from stock_input_lines as a
						left join stock_inputs as b on (a.input_id = b.input_id)
						where move_code = 'transfer'
						and input_close = 0
						group by store_code, product_code
				) as f on (f.product_code = a.product_code and f.store_code = a.store_code)
				left join (
						select sum(line_quantity) as in_transfer, product_code, store_code_transfer
						from stock_input_lines as a
						left join stock_inputs as b on (a.input_id = b.input_id)
						where move_code = 'transfer'
						and input_close = 0
						group by store_code_transfer, product_code
				) as g on (g.product_code = a.product_code and g.store_code_transfer = a.store_code)
				left join ref_unit_measures as h on (h.unit_code = b.unit_code)
				where stock_quantity<limit_min
			";

			if (!empty($request->store)) {
				$sql = $sql." and a.store_code = '".$request->store."'";
			}

			if (!empty($request->category_code)) {
				$sql = $sql." and b.category_code = '".$request->category_code."'";
			}

			$data = DB::select($sql);

			if ($request->export_report) {
				$data = collect($data)->map(function($x){ return (array) $x; })->toArray(); 
				DojoUtility::export_report($data);
			}
			
			/** Pagination **/
			$page = Input::get('page', 1); 
			$offSet = ($page * $this->paginateValue) - $this->paginateValue;
			$itemsForCurrentPage = array_slice($data, $offSet, $this->paginateValue, true);

			$data = new LengthAwarePaginator($itemsForCurrentPage, count($data), 
					$this->paginateValue, 
					$page, 
					['path' => $request->url(), 
					'query' => $request->query()]
			);
			return view('products.reorder', [
					'products'=>$data,
					'search'=>$request->search,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'store_code'=>$request->store,
					'stock_helper'=> new StockHelper(),
					'category_code'=>$request->category_code,
			]);
	}

	public function onHandEnquiry(Request $request)
	{
			/*** Base ***/
			//$products = Product::orderBy('product_name')
			//		->leftjoin('product_categories as c', 'c.category_code','=', 'products.category_code');

			$products = Inventory::orderBy('product_name')
							->leftJoin('products as b', 'b.product_code', '=', 'inventories.product_code')
							->groupBy('store_code', 'b.product_code');

			/*** Seach Param ***/
			if (!empty($request->search)) {
					$products = $products->where(function ($query) use ($request) {
							$search_param = trim($request->search, " ");
								$query->where('product_name','like','%'.$search_param.'%')
								->orWhere('product_name_other','like','%'.$search_param.'%')
								->orWhere('b.product_code','like','%'.$search_param.'%');
					});
			}

			/*** Category ***/
			if (!empty($request->category_code)) {
					$products = $products->where('category_code', $request->category_code);
			}

			/*** Batch Number ***/
			if (!empty($request->batch_number)) {
					$products = $products->where('inv_batch_number', $request->batch_number);
			}

			$products = $products->paginate($this->paginateValue);

			return view('products.on_hand', [
					'products'=>$products,
					'search'=>$request->search,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'store_code'=>$request->store,
					'stock_helper'=> new StockHelper(),
					'category_code'=>$request->category_code,
					'batch_number'=>$request->batch_number,
					]);

	}
	public function onHandEnquiry2(Request $request)
	{
			$sql = "
				select 	product_name, a.product_code, store_name, a.stock_quantity, allocated, (a.stock_quantity-allocated) as available, 
						b.product_average_cost, (b.product_average_cost*a.stock_quantity) as total_cost, unit_shortname, c.store_code
				from stock_stores as a
				left join products as b on (a.product_code = b.product_code)
				left join stores as c on (c.store_code = a.store_code)
				left join (
						select sum(order_quantity_request) as allocated, store_code, product_code
						from orders as a
						left join order_cancellations as b on (a.order_id = b.order_id)
						where order_completed=0
						and cancel_id is null
						group by store_code, product_code
				) as d on (d.store_code = a.store_code and d.product_code = a.product_code)
				left join ref_unit_measures as e on (e.unit_code = b.unit_code)
			";

			$sql = $sql."where (product_name like '%".$request->search."%' or a.product_code like '%".$request->search."%')";

			if (!empty($request->store)) {
				$sql = $sql." and a.store_code = '".$request->store."'";
			} else {
				$stores = Auth::user()->storeCodeInString();
				$sql = $sql." and a.store_code in (". $stores .") ";
			}

			if (!empty($request->category_code)) {
				$sql = $sql." and b.category_code = '".$request->category_code."'";
			}

			$data = DB::select($sql);

			if ($request->export_report) {
				$data = collect($data)->map(function($x){ return (array) $x; })->toArray(); 
				DojoUtility::export_report($data);
			}
			
			/** Pagination **/
			$page = Input::get('page', 1); 
			$offSet = ($page * $this->paginateValue) - $this->paginateValue;
			$itemsForCurrentPage = array_slice($data, $offSet, $this->paginateValue, true);

			$data = new LengthAwarePaginator($itemsForCurrentPage, count($data), 
					$this->paginateValue, 
					$page, 
					['path' => $request->url(), 
					'query' => $request->query()]
			);

			return view('products.on_hand', [
					'products'=>$data,
					'search'=>$request->search,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'store_code'=>$request->store,
					'stock_helper'=> new StockHelper(),
					'category_code'=>$request->category_code,
					]);
	}

}
