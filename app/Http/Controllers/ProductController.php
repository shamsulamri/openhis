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
	public $stores = null;

	public function __construct()
	{
			$this->stores = Auth::user()->storeList()->prepend('','');
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

			$store_code = null;
			if (!empty(Auth::user()->defaultStore($request)->store_code)) {
				$store_code = Store::find(Auth::user()->defaultStore($request))->store_code;
			}

				$store_code = Store::find(Auth::user()->defaultStore($request))->store_code;
			return view('products.index', [
					'products'=>$products,
					'loan'=>$loan,
					'categories'=>Auth::user()->categoryList(),
					'category_code'=>null,
					'helper'=>new StockHelper(),
					'store_code'=>$store_code,
					'stores'=>$this->stores,
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

			$helper = new StockHelper();
			$helper->updateStockOnHand($id);

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

			//$store = Store::find(Auth::user()->defaultStore($request));

			return view('products.index', [
					'products'=>$products,
					'search'=>$request->search,
					'loan'=>$loan,
					'stores'=>$this->stores,
					'categories'=>Auth::user()->categoryList(),
					'store_code'=>$request->store_code,
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
			$products = $products->where('deleted_at', null);
			$products = $products->paginate($this->paginateValue);

			return $products;
	}

	public function searchById(Request $request, $id)
	{
			$products = Product::where('product_code','=',$id)
					->paginate($this->paginateValue);

			$category_codes = Auth::user()->categoryCodes();

			$store = Store::find(Auth::user()->defaultStore($request));

			return view('products.index', [
					'products'=>$products,
					'store'=>$store,
					'categories'=>Auth::user()->categoryList(),
					'category_code'=>$request->category_code,
					'helper'=>new StockHelper(),
					'stores'=>$this->stores,
					'store_code'=>$request->store_code,
			]);
	}


	public function updateTotalOnHand($product_code) 
	{
		$stock_helper = new StockHelper();
		$stock_helper->updateStockOnHand($product_code);
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
						'store_code'=>Auth::user()->defaultStore($request),
				]);	
	}

	public function enquiry(Request $request)
	{
		$stock_helper = new StockHelper();
		$product = null;
		$store_code = null;

		if (!empty($request->search)) {
			$product = Product::where('product_code', $request->search);
			$product = $product->whereIn('category_code', Auth::user()->categoryCodes());
			$product = $product->first();
		}

		if (!empty($request->store_code)) {
				$store_code = $request->store_code;
		} else {
				$store_code = Auth::user()->defaultStore($request);
		}

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
				select product_name, a.product_code, a.store_code, store_name, sum(inv_quantity) as stock_quantity, limit_min, limit_max, unit_name, unit_shortname, on_purchase
				from inventories as a
				left join stock_limits as b on (a.product_code = b.product_code and b.store_code = a.store_code)
				left join products as c on (c.product_code = a.product_code)
				left join stores as d on (d.store_code = a.store_code)
				left join ref_unit_measures as e on (e.unit_code = a.unit_code)
				left join (
						select a.product_code, sum(line_quantity) as on_purchase
						from purchase_lines as a
						left join purchases as b on (b.purchase_id = a.purchase_id)
						left join inventories as c on (c.line_id = a.line_id)
						where document_code = 'purchase_order'
						and purchase_posted = 1
						and inv_id is null
						group by a.product_code
				) as f on (f.product_code = a.product_code)
				group by a.product_code, a.store_code, limit_min, limit_max, unit_name, unit_shortname
				having stock_quantity<limit_min
				";

			if (!empty($request->store_code)) {
				$sql = $sql." and a.store_code = '".$request->store_code."'";
			}

			if (!empty($request->category_code)) {
				$sql = $sql." and b.category_code = '".$request->category_code."'";
			}

			return $sql;
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
					'store_code'=>$request->store_code,
					'stock_helper'=> new StockHelper(),
					'category_code'=>$request->category_code,
			]);
	}

	public function onHandEnquiry2(Request $request)
	{
			/*** Base ***/

			$products = Inventory::orderBy('product_name')
							->leftJoin('products as b', 'b.product_code', '=', 'inventories.product_code')
							->groupBy('b.product_code')
							->groupBy('store_code');

			/*** Seach Param ***/
			if (!empty($request->search)) {
					$products = $products->where(function ($query) use ($request) {
							$search_param = trim($request->search, " ");
								$query->where('product_name','like','%'.$search_param.'%')
								->orWhere('product_name_other','like','%'.$search_param.'%')
								->orWhere('b.product_code','like','%'.$search_param.'%');
					});
			}

			/*** Store ***/
			if (!empty($request->store_code)) {
					$products = $products->where('store_code', $request->store_code);
			}

			/*** Batch Number ***/
			if (!empty($request->batch_number)) {
					$products = $products->where('inv_batch_number', $request->batch_number);
			}

			if ($request->export_report) {
				$products = $products->select('product_name','b.product_code');
				$products = $products->get()->toArray();
				//$data = collect($products)->map(function($x){ return (array) $x; })->toArray(); 
				DojoUtility::export_report($products);
			}

			$products = $products->paginate($this->paginateValue);

			if (empty($request->search)) {
				//$product = null;
			}

			return view('products.on_hand', [
					'products'=>$products,
					'search'=>$request->search,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'store_code'=>$request->store,
					'stock_helper'=> new StockHelper(),
					'store_code'=>$request->store_code,
					'batch_number'=>$request->batch_number,
					]);

	}

	public function onHandEnquiry(Request $request)
	{
			$sql = "
				select a.product_code, product_name, store_name, sum(inv_quantity) as on_hand, sum(inv_subtotal) as total_cost,
					 sum(inv_subtotal)/sum(inv_quantity) as average_cost, IFNULL(allocated, 0) as allocated ,sum(inv_quantity)-IFNULL(allocated,0) as available, unit_shortname, inv_batch_number
				from inventories a
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
				where inv_posted = 1
			";

			$sql = $sql."and (product_name like '%".$request->search."%' or a.product_code like '%".$request->search."%')";

			if (!empty($request->store_code)) {
				$sql = $sql." and a.store_code = '".$request->store_code."'";
			} else {
				$stores = Auth::user()->storeCodeInString();
				$sql = $sql." and a.store_code in (". $stores .") ";
			}

			if (!empty($request->category_code)) {
				$sql = $sql." and b.category_code = '".$request->category_code."'";
			}

			if (!empty($request->batch_number)) {
				$sql = $sql." and inv_batch_number = '".$request->batch_number."'";
			}

			$sql = $sql." group by a.store_code, product_code, inv_batch_number";

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

			$store_code = empty($request->store_code)?'':Store::find(Auth::user()->defaultStore($request))->store_code;
			//$store_code = $request->store_code;

			return view('products.on_hand', [
					'products'=>$data,
					'search'=>$request->search,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'store_code'=>$store_code,
					'stock_helper'=> new StockHelper(),
					'category_code'=>$request->category_code,
					'batch_number'=>$request->batch_number,
					]);
	}

}
