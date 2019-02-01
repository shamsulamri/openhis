<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\InventoryBatch;
use App\Product;
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
			$inventory_batches = InventoryBatch::orderBy('batch_expiry_date')
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

	public function index_product($id)
	{
			$product = Product::find($id);
			$inventory_batches = InventoryBatch::orderBy('batch_expiry_date')
					->where('product_code', $id)
					->paginate($this->paginateValue);

			return view('inventory_batches.index_product', [
					'inventory_batches'=>$inventory_batches,
					'product'=>$product,
			]);
	}

	public function add($product_code)
	{
			$product = Product::find($product_code);
			$inventory_batch = new InventoryBatch();
			$inventory_batch->product_code = $product->product_code;

			return view('inventory_batches.create', [
					'inventory_batch' => $inventory_batch,
					'source' => 'product',
					'product'=>$product,
					]);
	}
	public function store(Request $request) 
	{
			$inventory_batch = new InventoryBatch();
			$inventory_batch = new InventoryBatch($request->all());
			$valid = $inventory_batch->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$inventory_batch->batch_id = $request->batch_id;
					$inventory_batch->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/inventory_batches/product/'.$inventory_batch->product_code);
			} else {
					return redirect('/inventory_batches/add/'.$inventory_batch->product_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$inventory_batch = InventoryBatch::findOrFail($id);
			$product = $inventory_batch->product;
			return view('inventory_batches.edit', [
					'inventory_batch'=>$inventory_batch,
					'product'=>$product,
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
					return redirect('/inventory_batches/product/'.$inventory_batch->product_code);
			} else {
					return redirect('/inventory_batches/'.$id.'/edit')
							->withErrors($valid)
							->withInput();
			}
	}
	
	public function delete($id)
	{
		$inventory_batch = InventoryBatch::findOrFail($id);
		return view('inventory_batches.destroy', [
			'inventory_batch'=>$inventory_batch,
			'product'=>$inventory_batch->product,
			]);

	}
	public function destroy($id)
	{	
			$product_code = InventoryBatch::find($id)->product_code; 
			InventoryBatch::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/inventory_batches/product/'.$product_code);
	}
	
	public function search(Request $request)
	{
			$product = Product::find($request->product_code);
			$inventory_batches = InventoryBatch::orderBy('batch_expiry_date')
					->where('product_code', $product->product_code)
					->where('batch_number','like','%'.$request->search.'%')
					->paginate($this->paginateValue);

			return view('inventory_batches.index_product', [
					'inventory_batches'=>$inventory_batches,
					'product'=>$product,
			]);
	}

	public function search2(Request $request)
	{
			$inventory_batches = InventoryBatch::orderBy('batch_expiry_date')
					->leftJoin('products as b', 'inventory_batches.product_code', '=', 'b.product_code')
					->where('batch_number','like','%'.$request->search.'%')
					->orWhere('product_name', 'like','%'.$request->search.'%')
					->orWhere('b.product_code', 'like','%'.$request->search.'%')
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
