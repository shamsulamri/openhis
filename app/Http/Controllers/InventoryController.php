<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventory;
use Log;
use DB;
use Session;
use App\StockMovement as Move;
use App\Store;
use App\Product;
use App\ProductUom;
use App\StockHelper;
use App\InventoryMovement;
use App\InventoryBatch;
use Schema;

class InventoryController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$inventories = Inventory::orderBy('move_code')
					->paginate($this->paginateValue);

			return view('inventories.index', [
					'inventories'=>$inventories
			]);
	}

	public function detail($id)
	{
			$movement =InventoryMovement::find($id);
			$inventories = Inventory::orderBy('move_code')
					->where('move_id', $id)
					->paginate($this->paginateValue);

			return view('inventories.detail', [
					'inventories'=>$inventories,
					'helper'=>new StockHelper(),
					'move_id'=>$id,
					'movement'=>$movement,
			]);
	}

	public function create()
	{
			$inventory = new Inventory();
			return view('inventories.create', [
					'inventory' => $inventory,
					'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'store_transfer' => Store::all()->sortBy('store_transfer_name')->lists('store_transfer_name', 'store_transfer_code')->prepend('',''),
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$inventory = new Inventory();
			$valid = $inventory->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$inventory = new Inventory($request->all());
					$inventory->inv_id = $request->inv_id;
					$inventory->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/inventories/id/'.$inventory->inv_id);
			} else {
					return redirect('/inventories/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$inventory = Inventory::findOrFail($id);

			return view('inventories.edit', [
					'inventory'=>$inventory,
					'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'store_transfer' => Store::all()->sortBy('store_transfer_name')->lists('store_transfer_name', 'store_transfer_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$inventory = Inventory::findOrFail($id);
			$inventory->fill($request->input());


			$valid = $inventory->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$inventory->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/inventories/line/'.$id);
			} else {
					return redirect('inventories/'.$id.'/edit')
							->withErrors($valid)
							->withInput();
			}
	}
	
	public function delete($id)
	{
		$inventory = Inventory::findOrFail($id);
		return view('inventories.destroy', [
			'inventory'=>$inventory
			]);

	}
	public function destroy($id)
	{	
			$inventory = Inventory::find($id);
			Inventory::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/inventories/line/'.$inventory->move_id);
	}
	
	public function search(Request $request)
	{
			$inventories = DB::table('inventories')
					->where('move_code','like','%'.$request->search.'%')
					->orWhere('inv_id', 'like','%'.$request->search.'%')
					->orderBy('move_code')
					->paginate($this->paginateValue);

			return view('inventories.index', [
					'inventories'=>$inventories,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$inventories = DB::table('inventories')
					->where('inv_id','=',$id)
					->paginate($this->paginateValue);

			return view('inventories.index', [
					'inventories'=>$inventories
			]);
	}

	public function getOnHand($product_code) 
	{
			$helper = new StockHelper();
			$on_hand = $helper->getStockOnHand($product_code);
			$allocated = $helper->getStockAllocated($product_code);
			$completed = $helper->getStockCompleted($product_code);

			return $helper->getStockOnHand($product_code).'-'.$allocated.'-'.$completed;
	}
	
	public function deleteForm(Request $request, $move_id) 
	{
		$lines = Inventory::where('move_id', $move_id)->get();

		foreach ($lines as $line) {
				if ($request['chk_'.$line->inv_id] == '1') {
					Inventory::find($line->inv_id)->delete();
				}
		}
	}

	public function submit(Request $request, $move_id)
	{
		if ($request->button == 'Save') {
			Session::flash('message', 'Record Saved.');
			$this->saveForm($request, $move_id);
		} else {
			$this->deleteForm($request, $move_id);
			Session::flash('message', 'Record Deleted.');
		}

		return redirect('/inventories/detail/'.$move_id);
	}

	public function saveForm(Request $request, $move_id)
	{
		$movement = InventoryMovement::find($move_id);
		$lines = Inventory::where('move_id', $move_id)->get();

		foreach ($lines as $line) {
			$inventory = Inventory::find($line->inv_id);
			$inventory->unit_code = $request['unit_'.$line->inv_id]?:'unit';
			$inventory->uom_rate = 1;
			//$inventory->inv_book_quantity = $request['book_'.$line->inv_id];
			$inventory->inv_physical_quantity = $request['physical_'.$line->inv_id];
			$inventory->inv_quantity = $inventory->uom_rate*$inventory->inv_physical_quantity;
			$inventory->inv_batch_number = $request['batch_'.$line->inv_id];

			if (!empty($request['batch_'.$line->inv_id])) {
					$batch = InventoryBatch::where('batch_number', '=', $line->inv_batch_number)
								->where('product_code', $line->product_code)
								->first();

					if (empty($batch)) {
							$batch = new InventoryBatch();
							$batch->batch_number = $inventory->inv_batch_number;
							$batch->batch_expiry_date = $request['inv_expiry_date_'.$inventory->inv_id];
							$batch->product_code = $inventory->product_code;
							$batch->save();
							Log::info('----save----');
					} else {
							$batch->batch_expiry_date = $request['inv_expiry_date_'.$inventory->inv_id]?:NULL;
							$batch->save();
							//$batch->update(['batch_expiry_date'=>NULL]);
							Log::info($batch);
							Log::info('----update----');
					}
			}

			$product_uom = ProductUom::where('product_code','=',  $inventory->product_code)
								->where('unit_code', '=', $inventory->unit_code)
								->first();

			if (empty($product_uom)) {
					$product_uom = new ProductUom();
					$product_uom->uom_rate = 1;
					$product_uom->uom_cost = 0;
			}

			$subtotal = 0;

			if ($product_uom) {
				$inventory->uom_rate = $product_uom->uom_rate;
				if ($movement->move_code == 'adjust') {
						if ($inventory->inv_book_quantity>0) {
								$inventory->inv_quantity = ($inventory->inv_physical_quantity*$inventory->uom_rate)-$inventory->inv_book_quantity;
						} else {
								$inventory->inv_quantity = $inventory->inv_physical_quantity*$inventory->uom_rate;
						}
				} elseif ($movement->move_code == 'receive') {
						$inventory->inv_quantity = $inventory->inv_physical_quantity*$inventory->uom_rate;
				} else {
						$inventory->inv_quantity = -($inventory->inv_physical_quantity*$inventory->uom_rate);
				}
				$inventory->inv_unit_cost = $product_uom->uom_cost;
				$inventory->inv_subtotal = $inventory->inv_physical_quantity*$product_uom->uom_cost;
			}


			if ($inventory->inv_quantity<0) $subtotal = -$subtotal;

			$inventory->save();
		}

	}

	public function confirm($move_id)
	{
		return view('inventories.confirm', [
			'move_id'=>$move_id
			]);
	}

	public function post($move_id)
	{

			$inventories = Inventory::where('move_id', $move_id)->get();

			foreach($inventories as $line) {
					$line->inv_posted = 1;
					$line->save();
			}

			$movement = InventoryMovement::find($move_id);
			$movement->move_posted = 1;
			$movement->save();

			Session::flash('message', 'Transaction posted.');
			return redirect('/inventory_movements');
	}

}
