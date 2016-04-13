<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Stock;
use Log;
use DB;
use Session;
use App\StockMovement as Move;
use App\Store;
use App\Product;
use Carbon\Carbon;

class StockController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stocks = DB::table('stocks')
					->orderBy('product_code')
					->paginate($this->paginateValue);
			return view('stocks.index', [
					'stocks'=>$stocks
			]);
	}

	public function create($product_code)
	{
			$stock = new Stock();
			$stock->product_code = $product_code;
			$stock->stock_date = Carbon::now()->format('d/m/Y');
			return view('stocks.create', [
					'stock' => $stock,
					'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'product' => Product::find($product_code),
					]);
	}

	public function store(Request $request) 
	{
			$stock = new Stock();
			$valid = $stock->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock = new Stock($request->all());
					$stock->stock_id = $request->stock_id;
					$stock->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stocks/id/'.$stock->stock_id);
			} else {
					return redirect('/stocks/create/'.$request->product_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$stock = Stock::findOrFail($id);
			return view('stocks.edit', [
					'stock'=>$stock,
					'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'product' => $stock->product, 
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock = Stock::findOrFail($id);
			$stock->fill($request->input());


			$valid = $stock->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$stock->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stocks/id/'.$id);
			} else {
					return view('stocks.edit', [
							'stock'=>$stock,
							'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
							'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
							'product' => $stock->product, 
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$stock = Stock::findOrFail($id);
		return view('stocks.destroy', [
			'stock'=>$stock
			]);

	}
	public function destroy($id)
	{	
			Stock::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/stocks');
	}
	
	public function search(Request $request)
	{
			$stocks = DB::table('stocks')
					->where('product_code','like','%'.$request->search.'%')
					->orWhere('stock_id', 'like','%'.$request->search.'%')
					->orderBy('product_code')
					->paginate($this->paginateValue);

			return view('stocks.index', [
					'stocks'=>$stocks,
					'search'=>$request->search
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
}
