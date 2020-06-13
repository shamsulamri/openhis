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
use Auth;
use App\DojoUtility;

class InventoryController extends Controller
{
	public $paginateValue=100;

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

	public function detail(Request $request, $id)
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
					'submit'=>$request->submit,
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

		return redirect('/inventories/detail/'.$move_id.'?submit='.$request->button);
	}

	public function saveForm(Request $request, $move_id)
	{
		$movement = InventoryMovement::find($move_id);
		$lines = Inventory::where('move_id', $move_id)->get();

		foreach ($lines as $line) {
			$inventory = Inventory::find($line->inv_id);
			$inventory->unit_code = $request['unit_'.$line->inv_id]?:'unit';
			$inventory->uom_rate = 1;
			$inventory->inv_book_quantity = $request['book_'.$line->inv_id];
			$inventory->inv_batch_number = $request['batch_'.$line->inv_id];
			$inventory->inv_physical_quantity = $request['physical_'.$line->inv_id];
			$inventory->inv_quantity = $inventory->uom_rate*$inventory->inv_physical_quantity;

			if (!empty($request['batch_'.$line->inv_id])) {
					$batch = InventoryBatch::where('batch_number', '=', $request['batch_'.$line->inv_id])
								->where('product_code', $line->product_code)
								->first();
								//->where('batch_expiry_date', '=', DojoUtility::dateWriteFormat($request['inv_expiry_date_'.$line->inv_id]))

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
				if ($movement->move_code == 'stock_adjust') {
						if ($inventory->inv_book_quantity>0) {
								$inventory->inv_quantity = ($inventory->inv_physical_quantity*$inventory->uom_rate)-$inventory->inv_book_quantity;
						} else {
								$inventory->inv_quantity = $inventory->inv_physical_quantity*$inventory->uom_rate;
						}
				} elseif ($movement->move_code == 'stock_receive') {
						$inventory->inv_quantity = $inventory->inv_physical_quantity*$inventory->uom_rate;
				} else {
						$inventory->inv_quantity = -($inventory->inv_physical_quantity*$inventory->uom_rate);
				}
				$inventory->inv_unit_cost = $product_uom->uom_cost/$inventory->uom_rate;
				$inventory->inv_subtotal = $inventory->inv_quantity*$inventory->inv_unit_cost;
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

	public function enquiry(Request $request)
	{
			$inventories = Inventory::orderBy('inv_datetime', 'desc')
							->select('inv_datetime', 'b.product_code', 'b.product_name', 'c.move_name', 'inventories.move_description', 'inv_batch_number', 'inv_quantity', 'store_name', 'inv_subtotal', 'inv_unit_cost', 'f.encounter_id as origin_encounter','j.encounter_id as replicate_encounter', 'c.move_code', 'unit_name', 'inv_physical_quantity', 'inventories.unit_code', 'move_number','e.move_id', 'inv_id', 'h.purchase_id', 'i.purchase_number', 'order_parent_id', 'f.order_id')
							->leftJoin('products as b', 'b.product_code', '=', 'inventories.product_code')
							->leftJoin('stock_movements as c', 'c.move_code', '=', 'inventories.move_code')
							->leftJoin('stores as d', 'd.store_code', '=', 'inventories.store_code')
							->leftJoin('inventory_movements as e', 'e.move_id', '=', 'inventories.move_id')
							->leftjoin('orders as f', 'f.order_id', '=', 'inventories.order_id')
							->leftjoin('ref_unit_measures as g', 'g.unit_code', '=', 'inventories.unit_code')
							->leftjoin('purchase_lines as h', 'h.line_id', '=', 'inventories.line_id')
							->leftjoin('purchases as i', 'i.purchase_id', '=', 'h.purchase_id')
							->leftjoin('orders as j', 'j.order_id', '=', 'inventories.order_parent_id')
							->where('inv_posted', 1);

			/*** Batch ***/
			if (!empty($request->batch_number)) {
				$inventories = $inventories->where('inv_batch_number', '=', $request->batch_number);
			}

			/*** Store ***/
			if (!empty($request->store_code)) {
				$inventories = $inventories->where('inventories.store_code', $request->store_code);
			} else {
				$stores = Auth::user()->authorizedStores()->toArray();
				$inventories = $inventories->whereIn('inventories.store_code', $stores);
			}
			/*** Seach Param ***/
			if (!empty($request->search)) {
					$inventories = $inventories->where(function ($query) use ($request) {
							$search_param = trim($request->search, " ");
								$query->where('product_name','like','%'.$search_param.'%')
								->orWhere('product_name_other','like','%'.$search_param.'%')
								->orWhere('b.product_code','like','%'.$search_param.'%');
					});
			}

			if ($request->export_report) {
				$inventories = $inventories->select('inv_datetime','move_name', 'product_name','b.product_code', 'store_name', 'inv_batch_number','inv_quantity');
				$inventories = $inventories->get()->toArray();
				DojoUtility::export_report($inventories);
			}

			$inventories = $inventories->paginate($this->paginateValue);

			return view('inventories.enquiry', [
				'inventories'=>$inventories,
				'store'=>Auth::user()->storeList()->prepend('',''),
				'store_code'=>$request->store_code,
				'search'=>$request->search,
				'batch_number'=>$request->batch_number,
			]);
	}

}
