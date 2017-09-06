<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StockBatch;
use Log;
use DB;
use Session;


class StockBatchController extends Controller
{
	public $paginateValue=10;
	public $batch_status=[''=>'','available'=>'Available', 'unavailable'=>'Unavailable'];

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stock_batches = StockBatch::selectRaw('*, sum(batch_quantity) as total_quantity')
					->groupBy('store_code', 'product_code', 'batch_number', 'expiry_date')
					->orderBy('batch_id','desc')
					->paginate($this->paginateValue);

			$stock_batches=null;

			return view('stock_batches.index', [
					'stock_batches'=>$stock_batches,
					'status'=>$this->batch_status,
					'status_code'=>null,
			]);
	}

	public function create()
	{
			$stock_batch = new StockBatch();
			return view('stock_batches.create', [
					'stock_batch' => $stock_batch,
				
					]);
	}

	public function store(Request $request) 
	{
			$stock_batch = new StockBatch();
			$valid = $stock_batch->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock_batch = new StockBatch($request->all());
					$stock_batch->batch_id = $request->batch_id;
					$stock_batch->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_batches/id/'.$stock_batch->batch_id);
			} else {
					return redirect('/stock_batches/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$stock_batch = StockBatch::findOrFail($id);
			return view('stock_batches.edit', [
					'stock_batch'=>$stock_batch,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock_batch = StockBatch::findOrFail($id);
			$stock_batch->fill($request->input());


			$valid = $stock_batch->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$stock_batch->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stock_batches/id/'.$id);
			} else {
					return view('stock_batches.edit', [
							'stock_batch'=>$stock_batch,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$stock_batch = StockBatch::findOrFail($id);
		return view('stock_batches.destroy', [
			'stock_batch'=>$stock_batch
			]);

	}
	public function destroy($id)
	{	
			StockBatch::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/stock_batches');
	}
	
	public function search(Request $request)
	{
			$stock_batches = StockBatch::selectRaw('*, sum(batch_quantity) as total_quantity')
					->leftjoin('products', 'products.product_code','=', 'stock_batches.product_code')
					->where(function ($query) use ($request) {
									$query->orWhere('batch_number','like','%'.$request->search.'%')
									->orWhere('products.product_code','like','%'.$request->search.'%');
					})
					->groupBy('store_code', 'stock_batches.product_code', 'batch_number', 'expiry_date')
					->orderBy('batch_id','desc');

			if ($request->status_code == 'available') {
					$stock_batches = $stock_batches->havingRaw('sum(batch_quantity)>0');
			} elseif ($request->status_code == 'unavailable') {
					$stock_batches = $stock_batches->havingRaw('sum(batch_quantity)<=0');
			}

			$stock_batches = $stock_batches->paginate($this->paginateValue);

			return view('stock_batches.index', [
					'stock_batches'=>$stock_batches,
					'search'=>$request->search,
					'status'=>$this->batch_status,
					'status_code'=>$request->status_code,
					]);
	}

	public function searchById($id)
	{
			$stock_batches = DB::table('stock_batches')
					->where('batch_id','=',$id)
					->paginate($this->paginateValue);

			return view('stock_batches.index', [
					'stock_batches'=>$stock_batches
			]);
	}
}
