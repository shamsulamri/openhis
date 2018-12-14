<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\InventoryBatch;
use Log;
use DB;
use Session;


class InventoryBatchController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$inventory_batches = InventoryBatch::orderBy('batch_number')
					->paginate($this->paginateValue);

			return view('inventory_batches.index', [
					'inventory_batches'=>$inventory_batches
			]);
	}

	public function create()
	{
			$inventory_batch = new InventoryBatch();
			return view('inventory_batches.create', [
					'inventory_batch' => $inventory_batch,
				
					]);
	}

	public function store(Request $request) 
	{
			$inventory_batch = new InventoryBatch();
			$valid = $inventory_batch->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$inventory_batch = new InventoryBatch($request->all());
					$inventory_batch->batch_id = $request->batch_id;
					$inventory_batch->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/inventory_batches/id/'.$inventory_batch->batch_id);
			} else {
					return redirect('/inventory_batches/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$inventory_batch = InventoryBatch::findOrFail($id);
			return view('inventory_batches.edit', [
					'inventory_batch'=>$inventory_batch,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$inventory_batch = InventoryBatch::findOrFail($id);
			$inventory_batch->fill($request->input());


			$valid = $inventory_batch->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$inventory_batch->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/inventory_batches/id/'.$id);
			} else {
					return view('inventory_batches.edit', [
							'inventory_batch'=>$inventory_batch,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$inventory_batch = InventoryBatch::findOrFail($id);
		return view('inventory_batches.destroy', [
			'inventory_batch'=>$inventory_batch
			]);

	}
	public function destroy($id)
	{	
			InventoryBatch::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/inventory_batches');
	}
	
	public function search(Request $request)
	{
			$inventory_batches = DB::table('inventory_batches')
					->where('batch_number','like','%'.$request->search.'%')
					->orWhere('batch_id', 'like','%'.$request->search.'%')
					->orderBy('batch_number')
					->paginate($this->paginateValue);

			return view('inventory_batches.index', [
					'inventory_batches'=>$inventory_batches,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$inventory_batches = DB::table('inventory_batches')
					->where('batch_id','=',$id)
					->paginate($this->paginateValue);

			return view('inventory_batches.index', [
					'inventory_batches'=>$inventory_batches
			]);
	}
}
