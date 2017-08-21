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

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stock_batches = DB::table('stock_batches')
					->orderBy('batch_number')
					->paginate($this->paginateValue);
			return view('stock_batches.index', [
					'stock_batches'=>$stock_batches
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
			$stock_batches = DB::table('stock_batches')
					->where('batch_number','like','%'.$request->search.'%')
					->orWhere('batch_id', 'like','%'.$request->search.'%')
					->orderBy('batch_number')
					->paginate($this->paginateValue);

			return view('stock_batches.index', [
					'stock_batches'=>$stock_batches,
					'search'=>$request->search
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
