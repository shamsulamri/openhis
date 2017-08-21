<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StockLimit;
use Log;
use DB;
use Session;
use App\Product;
use App\Store;
use App\StoreAuthorization;
use Auth;

class StockLimitController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stock_limits = DB::table('stock_limits')
					->orderBy('limit_id')
					->paginate($this->paginateValue);
			return view('stock_limits.index', [
					'stock_limits'=>$stock_limits
			]);
	}

	public function create()
	{
			$stock_limit = new StockLimit();
			return view('stock_limits.create', [
					'stock_limit' => $stock_limit,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$stock_limit = new StockLimit();
			$valid = $stock_limit->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock_limit = new StockLimit($request->all());
					$stock_limit->limit_id = $request->limit_id;
					$stock_limit->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_limits/id/'.$stock_limit->limit_id);
			} else {
					return redirect('/stock_limits/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$stock_limit = StockLimit::findOrFail($id);
			return view('stock_limits.edit', [
					'stock_limit'=>$stock_limit,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock_limit = StockLimit::findOrFail($id);
			$stock_limit->fill($request->input());


			$valid = $stock_limit->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$stock_limit->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stock_limits/id/'.$id);
			} else {
					return view('stock_limits.edit', [
							'stock_limit'=>$stock_limit,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$stock_limit = StockLimit::findOrFail($id);
		return view('stock_limits.destroy', [
			'stock_limit'=>$stock_limit
			]);

	}
	public function destroy($id)
	{	
			StockLimit::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/stock_limits');
	}
	
	public function search(Request $request)
	{
			$stock_limits = DB::table('stock_limits')
					->where('limit_id','like','%'.$request->search.'%')
					->orWhere('limit_id', 'like','%'.$request->search.'%')
					->orderBy('limit_id')
					->paginate($this->paginateValue);

			return view('stock_limits.index', [
					'stock_limits'=>$stock_limits,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$stock_limits = DB::table('stock_limits')
					->where('limit_id','=',$id)
					->paginate($this->paginateValue);

			return view('stock_limits.index', [
					'stock_limits'=>$stock_limits
			]);
	}

	public function product($id)
	{
			$stores = StoreAuthorization::where('author_id', Auth::user()->author_id)->get();
			$limits = StockLimit::select('store_code', 'limit_min','limit_max')
					->where('product_code', $id)
					->get();

			$ids = $stores->pluck('store_code')->toArray();
			$ids = implode(";", $ids);

			$limits = $limits->keyBy('store_code');

			return view('stock_limits.limit_index', [
					'product'=>Product::find($id),
					'limits'=>$limits,
					'stores'=>$stores,
					'ids'=>$ids,
			]);
	}

	public function updateLimit(Request $request, $id)
	{
			$stores = explode(";", $request->stores);
			foreach($stores as $store_code) {
				$limit = StockLimit::where('store_code',$store_code)
								->where('product_code',$id)
								->first();

				Log::info(empty($limit));

				if (empty($limit)) {
						$limit = new StockLimit();
						$limit->store_code = $store_code;
						$limit->product_code = $id;
						$limit->limit_min = $request[$store_code."_min"];
						$limit->limit_max = $request[$store_code."_max"];
						$limit->save();
				} else {
						$limit->limit_min = $request[$store_code."_min"];
						$limit->limit_max = $request[$store_code."_max"];
						$limit->save();
				}
			}

			Session::flash('message', 'Record updated.');
			return redirect('/stock_limit/'.$id);
	}
}
