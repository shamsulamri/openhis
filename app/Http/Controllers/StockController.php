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
			return view('stocks.create', [
					'stock' => $stock,
					'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'stores' => Store::where('store_code','<>',$store_code)->orderBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'product' => Product::find($product_code),
					'store' => $store,
					'maxYear' => Carbon::now()->year,
					]);
	}

	public function store(Request $request) 
	{
			$stock = new Stock();


			$origin_date = $request->stock_datetime;
			if (DojoUtility::validateDateTime($request->stock_datetime)==true) {
					$stock_datetime = Carbon::createFromFormat('d/m/Y H:i', $request->stock_datetime);
					$stock_datetime = $stock_datetime->format('Y/m/d H:i');
					$request->stock_datetime = $stock_datetime;
			}

			$valid = $stock->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock = new Stock($request->all());
					$stock->username = Auth::user()->username;
					$stock->stock_id = $request->stock_id;

					switch($request->move_code) {
							case "receive":
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->save();
									break;
							case "dispose":
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->stock_quantity = -($stock->stock_quantity);
									$stock->save();
									break;
							case "return":
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->stock_quantity = -($stock->stock_quantity);
									$stock->save();
									break;
							case "transfer":
									$stock->stock_quantity = abs($stock->stock_quantity);
									$stock->stock_quantity = ($stock->stock_quantity)*-1;
									$stock->stock_description = "Transfer out";
									$stock->save();

									$transfer = new Stock();
									$transfer->product_code = $stock->product_code;
									$transfer->username = $stock->username;
									$transfer->stock_datetime = $origin_date;
									$transfer->move_code = $stock->move_code;
									$transfer->store_code_transfer = $stock->store_code;
									$transfer->store_code = $stock->store_code_transfer;
									$transfer->stock_quantity = abs($stock->stock_quantity);
									$transfer->stock_tag = $stock->stock_id;
									$transfer->stock_description = "Transfer in";
									$transfer->save();
									break;
							default:
									$stock->save();

					}

					
					$product = new ProductController();
					$product->updateTotalOnHand($stock->product_code);
					Session::flash('message', 'Record successfully created.');
					return redirect('/stocks/'.$stock->product_code.'/'.$stock->store_code);
			} else {
					return redirect('/stocks/create/'.$request->product_code.'/'.$request->store_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function updateTotalOnHand($product_code)
	{
			$product = new ProductController();
			return $product->updateTotalOnHand($product_code);
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
					$product = new ProductController();
					$product->updateTotalOnHand($stock->product_code);
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
			$product = new ProductController();
			$product->updateTotalOnHand($stock->product_code);
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
			if (Auth::user()->cannot('module-inventory')) {
					$ward_code = $request->cookie('ward');
					$ward = Ward::where('ward_code', $ward_code)->first();
					$store_code = $ward->store_code;
			}

			$stocks = Stock::orderBy('stock_datetime','desc')
					->leftJoin('stock_movements as b', 'b.move_code', '=','stocks.move_code')
					->leftJoin('stores as c', 'c.store_code', '=','stocks.store_code')
					->where('product_code','=',$product_code)
					->where('stocks.store_code','=',$store_code)
					->orderBy('stock_id','desc')
					->paginate($this->paginateValue);

			$product = Product::find($product_code);
			$store = Store::find($store_code);

			$stores = Store::orderBy('store_name')
							->select('stores.store_code', 'stock_stores.stock_quantity')
							->leftjoin('stock_stores', function ($query) use($product_code) {
									$query->where('stock_stores.product_code','=', $product_code);
									$query->where('stock_stores.store_code','=', 'stores.store_code');
							});

			$sql = sprintf("select a.store_code, store_name,  stock_quantity from stores a 
						left join stock_stores b on (b.product_code = '%s' and b.store_code = a.store_code)", $product_code);

			$stores = DB::select($sql);

			return view('stocks.index', [
					'stocks'=>$stocks,
					'product'=>$product,
					'store_code'=>$store_code,
					'stores' => $stores,
					'stockHelper' => new StockHelper(),
					'store'=>$store,
			]);
	}
	
}
