<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Stock;
use Log;
use DB;
use Session;
use App\StockMovement as Move;
use App\Store;
use App\Product;
use Carbon\Carbon;
use App\DojoUtility;
use App\StockHelper;
use App\Ward;
use Auth;
use App\StoreAuthorization;
use App\StockBatch;
use App\StockStore;

class StockController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$store_code = Auth::user()->defaultStore();
			$stocks = Stock::select('move_name', 'stock_datetime', 'e.store_name as store_from', 'f.store_name as store_to', 
						'product_name', 'products.product_code','stock_quantity', 'stock_value', 'stocks.move_code'
						)
						->leftjoin('products', 'products.product_code','=', 'stocks.product_code')
						->leftjoin('product_categories as c', 'c.category_code','=', 'products.category_code')
						->leftjoin('stock_movements as d', 'd.move_code', '=', 'stocks.move_code')
						->leftjoin('stores as e', 'e.store_code', '=', 'stocks.store_code')
						->leftjoin('stores as f', 'f.store_code', '=', 'stocks.store_code_transfer')
						->orderBy('stock_id', 'desc')
						->where('stocks.store_code', $store_code)
						->orderBy('stock_id', 'desc');

			$category_codes = Auth::user()->categoryCodes();
			if (count($category_codes)>0) {
					$stocks = $stocks->whereIn('products.category_code',$category_codes);
			}

			$stocks = $stocks->paginate($this->paginateValue);

			return view('stocks.index', [
					'stocks'=>$stocks,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'category_code'=>null,
					'store_code'=>$store_code,
					'search'=>null,
					'date_start'=>null,
					'date_end'=>null,
					'move' => Move::where('move_code','<>','sale')->orderBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'move_code'=>null,
			]);
	}

	public function create($product_code, $store_code)
	{
			$stock = new Stock();
			$stock->product_code = $product_code;
			$store = Store::find($store_code);
			$batches = StockBatch::selectRaw('sum(batch_quantity) as batch_quantity, expiry_date, batch_number')
						->where('product_code','=', $product_code)
						->groupBy('batch_number')
						->orderBy('expiry_date')
						->get();

			return view('stocks.create', [
					'stock' => $stock,
					'move' => Move::where('move_code','<>','sale')->orderBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'stores' => Store::where('store_code','<>',$store_code)->orderBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'product' => Product::find($product_code),
					'store' => $store,
					'store_code'=>$store_code,
					'maxYear' => Carbon::now()->year,
					'stock_helper' => new StockHelper(),
					'batches'=>$batches,
					]);
	}

	public function store(Request $request) 
	{
			$stock_helper = new StockHelper();
			$stock = new Stock();

			$product = Product::find($request->product_code);

			$batches = $stock_helper->getBatches($product->product_code);

			/** Validate batches **/
			$batch_quantity = 0;
			if ($product->product_track_batch==1) {
					foreach($batches as $batch) {
						$batch_quantity += $request[$product->product_code.'_'.$batch->batch_number];
						Log::info($batch_quantity);
					}

					$batch_quantity += $request['batch_quantity_new'];

					if ($batch_quantity != abs($request->stock_quantity)) {
							Session::flash('error', 'Total batch quantity must equal to global quantity.');
							return redirect('/stocks/create/'.$request->product_code.'/'.$request->store_code)
									->withInput();
					}
			}
			/** end **/


			$valid = $stock->validate($request->all(), $request->_method);

			$validQuantity=True;
			$quantityControls = array('adjust','transfer','dispose','return');
			if (in_array($request->move_code,$quantityControls)) {
					if (abs($request->stock_quantity)>$product->product_on_hand) {
							$validQuantity=False;
					}
			}

			if ($valid->passes() && $validQuantity) {
					$stock = new Stock($request->all());
					$stock->username = Auth::user()->username;

					$stock = $stock_helper->moveStock($stock);

					if ($product->product_track_batch==1) {
							$stock_helper->moveStockBatch($request, $stock);
					}

					Session::flash('message', 'Record successfully created.');
					return redirect('/stocks/'.$stock->product_code.'/'.$stock->store_code);
			} else {
					if (!$validQuantity) {
						$valid->getMessageBag()->add('stock_quantity','Amount greater than item on hand.');
					}
					return redirect('/stocks/create/'.$request->product_code.'/'.$request->store_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function store2(Request $request) 
	{

			$stock = new Stock();

			$product = Product::find($request->product_code);

			$batches = StockBatch::selectRaw('sum(batch_quantity) as batch_quantity, expiry_date, batch_number, product_code')
						->where('product_code','=', $product->product_code)
						->groupBy('batch_number')
						->orderBy('expiry_date','batch_number')
						->get();

			/** Validate quantity & batch quantity **/
			$batch_quantity = 0;
			if ($product->product_track_batch==1) {
					foreach($batches as $batch) {
						$batch_quantity += $request['batch_quantity_'.$batch->batch_number];
						Log::info($batch_quantity);
					}

					$batch_quantity += $request['batch_quantity_new'];

					Log::info('---'.$batch_quantity);
					if ($batch_quantity != abs($request->stock_quantity)) {
							Session::flash('error', 'Quantity does not equal to the total batch quantity.');
							return redirect('/stocks/create/'.$request->product_code.'/'.$request->store_code)
									->withInput();
					}
			}

			/** end **/

			/**
			$origin_date = $request->stock_datetime;
			if (DojoUtility::validateDateTime($request->stock_datetime)==true) {
					$stock_datetime = Carbon::createFromFormat('d/m/Y H:i', $request->stock_datetime);
					$stock_datetime = $stock_datetime->format('Y/m/d H:i');
					$request->stock_datetime = $stock_datetime;
			}
			**/

			$valid = $stock->validate($request->all(), $request->_method);

			$validQuantity=True;
			$quantityControls = array('adjust','transfer','dispose','return');
			if (in_array($request->move_code,$quantityControls)) {
					if (abs($request->stock_quantity)>$product->product_on_hand) {
							$validQuantity=False;
					}
			}

			if ($valid->passes() && $validQuantity) {
					if ($request->move_code=='take') {
							Stock::where('product_code','=', $request->product_code)
									->where('store_code', '=', $request->store_code)
									->delete();
					}
					$stock = new Stock($request->all());
					$stock->username = Auth::user()->username;
					$stock->stock_id = $request->stock_id;

					$is_negetive = FALSE;
					switch($request->move_code) {
							case "loan_in":
									$is_negetive=FALSE;
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->save();
									break;
							case "loan_out":
									$is_negetive=TRUE;
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->stock_quantity = -($stock->stock_quantity);
									$stock->stock_value = -($stock->stock_value);
									$stock->save();
									break;
							case "receive":
									$is_negetive=FALSE;
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->save();
									break;
							case "dispose":
									$is_negetive=TRUE;
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->stock_quantity = -($stock->stock_quantity);
									$stock->stock_value = -($stock->stock_value);
									$stock->save();
									break;
							case "return":
									$is_negetive=TRUE;
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->stock_quantity = -($stock->stock_quantity);
									$stock->stock_value = -($stock->stock_value);
									$stock->save();
									break;
							case "transfer":
									$is_negetive=TRUE;
									$store = Store::find($stock->store_code_transfer);
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->stock_quantity = ($stock->stock_quantity)*-1;
									$stock->stock_description = "Transfer out to ".$store->store_name;
									$stock->stock_value = -($stock->stock_value);
									$stock->save();

									$store = Store::find($stock->store_code);
									$transfer = new Stock();
									$transfer->product_code = $stock->product_code;
									$transfer->username = $stock->username;
									//$transfer->stock_datetime = $origin_date;
									$transfer->move_code = $stock->move_code;
									$transfer->store_code_transfer = $stock->store_code;
									$transfer->store_code = $stock->store_code_transfer;
									$transfer->stock_quantity = abs($stock->stock_quantity);
									$transfer->stock_value = abs($stock->stock_value);
									$transfer->stock_tag = $stock->stock_id;
									$transfer->stock_description = "Transfer in from ".$store->store_name;
									$transfer->save();
									break;
							default:
									if ($request->stock_quantity<0) $is_negetive=TRUE;
									if ($is_negetive) {
											$stock->stock_value = -($stock->stock_value);
									}
									$stock->save();

					}



					if ($product->product_track_batch==1) {
							if ($stock->move_code=='take') {
									StockBatch::where('product_code','=', $stock->product_code)->delete();
							}
							foreach($batches as $batch) {
									$batch_quantity = $request['batch_quantity_'.$batch->batch_number];
									$batch_quantity = abs($batch_quantity);

									if ($is_negetive) {
										$batch_quantity = abs($batch_quantity)*-1;
									}

									if (!empty($batch_quantity)) {

											$new_batch = new StockBatch();
											$new_batch->stock_id = $stock->stock_id;
											$new_batch->product_code = $batch->product_code;
											$new_batch->batch_number = $batch->batch_number;
											$new_batch->batch_quantity = $batch_quantity;
											$new_batch->expiry_date = $batch->expiry_date;
											$new_batch->save();
									}
							}

							if (!empty($request->batch_number_new)) {
									$new_batch = new StockBatch();
									$new_batch->stock_id = $stock->stock_id;
									$new_batch->product_code = $stock->product_code;
									$new_batch->batch_number = $request->batch_number_new;
									$new_batch->batch_quantity = $request->batch_quantity_new;
									$new_batch->expiry_date = DojoUtility::dateWriteFormat($request->batch_expiry_date_new);
									$new_batch->save();
							}
					}
					
					$helper = new StockHelper();
					$helper->updateAllStockOnHand($stock->product_code);

					Session::flash('message', 'Record successfully created.');
					return redirect('/stocks/'.$stock->product_code.'/'.$stock->store_code);
			} else {
					if (!$validQuantity) {
						$valid->getMessageBag()->add('stock_quantity','Amount greater than item on hand.');
					}
					return redirect('/stocks/create/'.$request->product_code.'/'.$request->store_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function updateTotalOnHand($product_code)
	{
			$helper = new StockHelper();
			$helper->updateAllStockOnHand($product_code);
			$product = Product::find($product_code);
			return $product->product_on_hand;
			//$product = new ProductController();
			//return $product->updateTotalOnHand($product_code);
	}


	public function edit($id) 
	{
			$stock = Stock::findOrFail($id);
			$stock->stock_date =  date('d/m/Y', strtotime($stock->stock_datetime));
			$store = Store::find($stock->store_code);
			return view('stocks.edit', [
					'stock'=>$stock,
					'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'product' => $stock->product, 
					'store' => $store,
					'stores' => Store::where('store_code','<>',$stock->store_code)->orderBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'maxYear' => Carbon::now()->year,
					'stock_helper' => new StockHelper(),
					'store_code'=>$stock->store_code,
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock = Stock::findOrFail($id);
			$stock->fill($request->input());
			$store = Store::find($request->store_code);
			$valid = $stock->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$stock->save();
					$helper = new StockHelper();
					$helper->updateAllStockOnHand($stock->product_code);
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stocks/'.$stock->product_code.'/'.$stock->store_code);
			} else {
					return view('stocks.edit', [
							'stock'=>$stock,
							'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
							'store' => $store,
							'product' => $stock->product, 
							'maxYear' => Carbon::now()->year,
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$stock = Stock::findOrFail($id);
		return view('stocks.destroy', [
			'stock'=>$stock,
			'product' => $stock->product, 
			]);

	}
	public function destroy($id)
	{	
			$stock = Stock::find($id);
			Stock::find($id)->delete();
			$helper = new StockHelper();
			$helper->updateAllStockOnHand($stock->product_code);
			Session::flash('message', 'Record deleted.');
			return redirect('/stocks/'.$stock->product_code);
	}
	
	public function search(Request $request)
	{
			$stocks = Stock::select('move_name', 'stock_datetime', 'e.store_name as store_from', 'f.store_name as store_to', 
						'product_name', 'products.product_code','stock_quantity', 'stock_value', 'stocks.move_code'
						)
						->leftjoin('products', 'products.product_code','=', 'stocks.product_code')
						->leftjoin('product_categories as c', 'c.category_code','=', 'products.category_code')
						->leftjoin('stock_movements as d', 'd.move_code', '=', 'stocks.move_code')
						->leftjoin('stores as e', 'e.store_code', '=', 'stocks.store_code')
						->leftjoin('stores as f', 'f.store_code', '=', 'stocks.store_code_transfer')
						->orderBy('stock_id', 'desc');

			/*** Store ***/
			if (!empty($request->store_code)) {
					$stocks = $stocks->where('stocks.store_code', $request->store_code);
			}

			/*** Movement ***/
			if (!empty($request->move_code)) {
					$stocks = $stocks->where('stocks.move_code', $request->move_code);
			}

			/*** Category ***/
			$category_codes = Auth::user()->categoryCodes();
			if (!empty($request->category_code)) {
					$stocks = $stocks->where('products.category_code','=', $request->category_code);
			} else {
					if (count($category_codes)>0) {
							$stocks = $stocks->whereIn('products.category_code',$category_codes);
					}
			}

			/*** Date Range ****/
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			if (isset($date_start) & isset($date_end)) {
					$stocks = $stocks->whereBetween('stocks.created_at', [$date_start.' 00:00', $date_end.' 23:59']);
			}	

			if (isset($date_start) & !isset($date_end)) {
					$stocks = $stocks->where('stocks.created_at', ">=", $date_start.' 00:00');
			}	

			if (!isset($date_start) & isset($date_end)) {
					$stocks = $stocks->where('stocks.created_at', "<=", $date_end.' 23:59');
			}	

			/*** Seach Param ***/
			if (!empty($request->search)) {
					$stocks = $stocks->where(function ($query) use ($request) {
								$query->where('product_name','like','%'.$request->search.'%')
								->orWhere('product_name_other','like','%'.$request->search.'%')
								->orWhere('products.product_code','like','%'.$request->search.'%');
					});
			}


			if ($request->export_report) {
				DojoUtility::export_report($stocks->get());
			}
			$stocks = $stocks->paginate($this->paginateValue);

			return view('stocks.index', [
					'stocks'=>$stocks,
					'store_code'=>$request->store_code,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'categories'=>Auth::user()->categoryList(),
					'category_code'=>$request->category_code,
					'store_code'=>$request->store_code,
					'search'=>$request->search,
					'date_start'=> $date_start,
					'date_end'=> $date_end,
					'move_code'=> $request->move_code,
					'move' => Move::where('move_code','<>','sale')->orderBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
			]);
	}

	public function searchById($id)
	{
			$stocks = DB::table('stocks')
					->where('stock_id','=',$id)
					->paginate($this->paginateValue);

			return view('stocks.index', [
					'stocks'=>$stocks
			]);
	}

	public function show(Request $request, $product_code, $store_code=null)
	{
			if ($store_code == null or empty($store_code)) {
					$store_code = $this->getDefaultStore($request);
			} 

			$stocks = Stock::orderBy('stock_id','desc')
					->leftJoin('stock_movements as b', 'b.move_code', '=','stocks.move_code')
					->leftJoin('stores as c', 'c.store_code', '=','stocks.store_code')
					->where('product_code','=',$product_code)
					->where('stocks.store_code','=',$store_code)
					->orderBy('stock_id','desc')
					->withTrashed()
					->paginate($this->paginateValue);

			$product = Product::find($product_code);

			$stores = StockStore::where('product_code', $product_code)
						->whereIn('store_code', Auth::user()->storeCodes())
						->get();

			$store = Store::find($store_code);

			if (empty($store_code)) {
				return "Floor store for this ward has not been defined.";
			}

			return view('stocks.show', [
					'stocks'=>$stocks,
					'product'=>$product,
					'store_code'=>$store_code,
					'stores' => $stores,
					'stockHelper' => new StockHelper(),
					'store'=>$store,
			]);
	}
	
	public function getDefaultStore(Request $request) 
	{
			$default_store = null;
			if (Auth::user()->authorization->store_code) {
				$default_store = Auth::user()->authorization->store_code;
			}

			if (Auth::user()->cannot('module-inventory') or empty($default_store)) {
					$ward_code = $request->cookie('ward');
					$ward = Ward::find($ward_code);
					if ($ward) {
						$default_store = $ward->store_code;
					} 
			} 		

			return $default_store;
	}

}
