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

class StockController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stocks = Stocks::where('product_code','=', $product_code)
					->orderBy('product_code')
					->paginate($this->paginateValue);

			return view('stocks.index', [
					'stocks'=>$stocks
			]);
	}

	public function create($product_code, $store_code)
	{
			$stock = new Stock();
			$stock->product_code = $product_code;
			$stock->stock_datetime = DojoUtility::now();
			$store = Store::find($store_code);
			$batches = StockBatch::selectRaw('sum(batch_quantity) as batch_quantity, expiry_date, batch_number')
						->where('product_code','=', $product_code)
						->groupBy('batch_number')
						->orderBy('expiry_date')
						->get();

			return view('stocks.create', [
					'stock' => $stock,
					'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
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

			$origin_date = $request->stock_datetime;
			if (DojoUtility::validateDateTime($request->stock_datetime)==true) {
					$stock_datetime = Carbon::createFromFormat('d/m/Y H:i', $request->stock_datetime);
					$stock_datetime = $stock_datetime->format('Y/m/d H:i');
					$request->stock_datetime = $stock_datetime;
			}

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
					$stock->stock_id = $request->stock_id;

					$is_negetive = FALSE;
					switch($request->move_code) {
							case "receive":
									$is_negetive=FALSE;
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->save();
									break;
							case "dispose":
									$is_negetive=TRUE;
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->stock_quantity = -($stock->stock_quantity);
									$stock->save();
									break;
							case "return":
									$is_negetive=TRUE;
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->stock_quantity = -($stock->stock_quantity);
									$stock->save();
									break;
							case "transfer":
									$is_negetive=TRUE;
									$store = Store::find($stock->store_code_transfer);
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->stock_quantity = ($stock->stock_quantity)*-1;
									$stock->stock_description = "Transfer out to ".$store->store_name;
									$stock->save();

									$store = Store::find($stock->store_code);
									$transfer = new Stock();
									$transfer->product_code = $stock->product_code;
									$transfer->username = $stock->username;
									$transfer->stock_datetime = $origin_date;
									$transfer->move_code = $stock->move_code;
									$transfer->store_code_transfer = $stock->store_code;
									$transfer->store_code = $stock->store_code_transfer;
									$transfer->stock_quantity = abs($stock->stock_quantity);
									$transfer->stock_tag = $stock->stock_id;
									$transfer->stock_description = "Transfer in from ".$store->store_name;
									$transfer->save();
									break;
							default:
									if ($request->stock_quantity<0) $is_negetive=TRUE;
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
			$stocks = DB::table('stocks as a')
					->leftJoin('stock_movements as b', 'b.move_code', '=','a.move_code')
					->leftJoin('stores as c', 'c.store_code', '=','a.store_code')
					->where('product_code','=',$request->product_code)
					->where('a.store_code','=',$request->store_code)
					->orderBy('stock_datetime', 'desc')
					->paginate($this->paginateValue);
			$product = Product::find($request->product_code);
			return view('stocks.index', [
					'stocks'=>$stocks,
					'product'=>$product,
					'store_code'=>$request->store_code,
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
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

			$stocks = Stock::orderBy('stock_datetime','desc')
					->leftJoin('stock_movements as b', 'b.move_code', '=','stocks.move_code')
					->leftJoin('stores as c', 'c.store_code', '=','stocks.store_code')
					->where('product_code','=',$product_code)
					->where('stocks.store_code','=',$store_code)
					->orderBy('stock_id','desc')
					->paginate($this->paginateValue);

			$product = Product::find($product_code);

			/*
			$stores = Store::orderBy('store_name')
							->select('stores.store_code', 'stock_stores.stock_quantity')
							->leftjoin('stock_stores', function ($query) use($product_code) {
									$query->where('stock_stores.product_code','=', $product_code);
									$query->where('stock_stores.store_code','=', 'stores.store_code');
							});
			*/

			$sql = sprintf("select a.store_code, store_name,  stock_quantity from store_authorizations a 
						left join stores c on (c.store_code = a.store_code)
						left join stock_stores b on (b.product_code = '%s' and b.store_code = a.store_code)
						where author_id = %d", $product_code, Auth::user()->authorization->author_id);

			$stores = DB::select($sql);


			/**
			$stores = StoreAuthorization::where('author_id', Auth::user()->author_id)
							->leftjoin('stores as b', 'b.store_code','=', 'store_authorizations.store_code')
							->orderBy('store_name')
							->get();
			**/

			$store = Store::find($store_code);

			//$stores = $stores->lists('store_name', 'b.store_code')
							//->prepend('','');
							//
			if (empty($store_code)) {
				return "Floor store for this ward has not been defined.";
			}

			return view('stocks.index', [
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
