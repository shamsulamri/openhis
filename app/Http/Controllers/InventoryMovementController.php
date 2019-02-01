<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\InventoryMovement;
use Log;
use DB;
use Session;
use App\StockMovement as Move;
use App\Store;
use Auth;
use App\Inventory;
use App\StockHelper;
use App\StockMovement;
use App\StockTag;

class InventoryMovementController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$store_code =  Auth::user()->defaultStore($request);
			$inventory_movements = InventoryMovement::orderBy('move_posted')
					->where('store_code', '=',  $store_code)
					->orderBy('move_id', 'desc')
					->paginate($this->paginateValue);

			return view('inventory_movements.index', [
					'inventory_movements'=>$inventory_movements
			]);
	}

	public function masterDocument(Request $request, $id)
	{
			$movement = InventoryMovement::find($id);

			$documents = InventoryMovement::orderBy('move_posted')
					->orderBy('move_id', 'desc')
					->where('move_posted', 1)
					->where('move_id', '<>', $id)
					->paginate($this->paginateValue);


			return view('inventory_movements.master_document',[
				'movement'=>$movement,
				'documents'=>$documents,
				'reload'=>$request->reload,
				'reason'=>'stock',
			]);

	}

	public function masterItem(Request $request, $id, $document_id = null)
	{
			$store_code =  Auth::user()->defaultStore($request);
			$inventories = Inventory::where('move_posted', 1)
							->leftJoin('inventory_movements as b', 'b.move_id', '=', 'inventories.move_id');
							

			if (!empty($document_id)) {
					$inventories = $inventories->where('b.move_id', $document_id);

			}

			$inventories = $inventories->paginate($this->paginateValue);

			$movement = InventoryMovement::find($id);

			$movement_from = InventoryMovement::find($document_id)?:null;

			return view('inventory_movements.master_item',[
				'movement'=>$movement,	
				'inventories'=>$inventories,
				'reload'=>$request->reload,
				'document_id'=>$document_id,
				'reason'=>'stock',
				'movement_from'=>$movement_from,
			]);

	}

	public function searchItem(Request $request)
	{
			$inventories = Inventory::where('move_posted', 1)
							->leftJoin('inventory_movements as b', 'b.move_id', '=', 'inventories.move_id')
							->leftJoin('products as c', 'c.product_code', '=', 'inventories.product_code');
							

			if (!empty($request->from)) {
					$inventories = $inventories->where('b.move_id', $request->from);

			}

			if (!empty($request->search)) {
					$inventories = $inventories->where(function ($query) use ($request) {
							$query->where('inventories.product_code','like','%'.$request->search.'%')
								->orWhere('c.product_name','like','%'.$request->search.'%');
					});
			}

			$inventories = $inventories->paginate($this->paginateValue);

			$movement = InventoryMovement::find($request->to);
			$movement_from = InventoryMovement::find($request->from)?:null;

			return view('inventory_movements.master_item',[
				'movement'=>$movement,	
				'inventories'=>$inventories,
				'reload'=>$request->reload,
				'document_id'=>$request->from,
				'reason'=>'stock',
				'search'=>$request->search,
				'movement_from'=>$movement_from,
			]);
	}


	public function convert(Request $request, $from, $to) 
	{
			$reason = $request->reason;
			$items = Inventory::where('move_id', '=', $from)->get();

			foreach ($items as $item) {
				$this->convertItem($item->inv_id, $to);
			}

			return redirect('/inventory_movements/master_document/'.$to.'?reason=stock&reload=true');
	}

	public function convertItem($from, $to)
	{
			$item = Inventory::find($from);

			$helper = new StockHelper();

			if ($item) {
					$movement = InventoryMovement::find($to);
					$inventory = new Inventory();
					$inventory = $item->replicate();
					$inventory->move_id = $to;
					$inventory->store_code = $movement->store_code;
					$inventory->move_reference = $item->move_id;
					$inventory->move_code = $movement->move_code;
					$inventory->inv_book_quantity = $helper->getStockOnHand($item->product_code, $movement->store_code);
					Log::info($movement->store_code);
					$inventory->inv_posted = 0;
					if ($movement->move_code == 'stock_receive') {
							$inventory->inv_quantity = abs($inventory->inv_quantity);
					}
					$inventory->save();
			}
	}

	public function show(Request $request, $id)
	{
			$movement = InventoryMovement::find($id);
					
			return view('inventory_movements.show', [
					'movement'=>$movement,
					'reason'=>$request->reason,
			]);
	}

	public function create(Request $request)
	{

			$inventory_movement = new InventoryMovement();
			$inventory_movement->store_code =  Auth::user()->defaultStore($request);

			$tags = StockTag::select(DB::raw("tag_name, tag_code, move_code"))->get();

			return view('inventory_movements.create', [
					'inventory_movement' => $inventory_movement,
					'move' => StockMovement::where('move_code', '<>', 'sale')
								->where('move_code', '<>', 'goods_receive')
								->orderBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'store'=>Auth::user()->storeList()->prepend('',''),
					'target_stores' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'tag' => StockTag::all()->sortBy('tag_name')->lists('tag_name', 'tag_code')->prepend('',''),
					'tags' => $tags,
					]);
	}

	public function store(Request $request) 
	{
			$inventory_movement = new InventoryMovement();
			$valid = $inventory_movement->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$inventory_movement = new InventoryMovement($request->all());
					$inventory_movement->user_id = Auth::user()->id;
					$inventory_movement->save();
					$this->updateDocumentNumber($inventory_movement->move_id);

					Session::flash('message', 'Record successfully created.');
					return redirect('/inventory_movements/show/'.$inventory_movement->move_id);
			} else {
					return redirect('/inventory_movements/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function updateDocumentNumber($move_id) {


			$movement = InventoryMovement::findOrFail($move_id);
			$prefix = $movement->movement->move_prefix;

			$table = 'rn_'.$movement->move_code;

			DB::insert('insert into '.$table.' (move_id) values (?)',
					[$move_id]);

			$results = DB::select('select * from '.$table.' where move_id='.$move_id);
			$id = $results[0]->id;

			$number = str_pad($id, 8, '0', STR_PAD_LEFT);

			$movement->move_number = $prefix. '-'.$number;
			$movement->save();

			$affected = DB::update('update '.$table.' set document_number= ? where id=?', [$movement->move_number, $id]);
			Log::info($affected);

	}

	public function edit($id) 
	{
			$inventory_movement = InventoryMovement::findOrFail($id);
			return view('inventory_movements.edit', [
					'inventory_movement'=>$inventory_movement,
					'move' => array(''=>'','stock_adjust'=>'Stock Adjustment', 'stock_receive'=>'Stock Receive', 'stock_issue'=>'Stock Issue'),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'store_transfer' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'tag' => StockTag::all()->sortBy('tag_name')->lists('tag_name', 'tag_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$inventory_movement = InventoryMovement::findOrFail($id);
			$inventory_movement->fill($request->input());

			$valid = $inventory_movement->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$inventory_movement->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/inventory_movements/id/'.$id);
			} else {
					return view('inventory_movements.edit')
							->withInput()
							->withErrors($valid);			

			}
	}
	
	public function delete($id)
	{
		$inventory_movement = InventoryMovement::findOrFail($id);
		return view('inventory_movements.destroy', [
			'inventory_movement'=>$inventory_movement
			]);

	}
	public function destroy($id)
	{	
			InventoryMovement::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/inventory_movements');
	}
	
	public function search(Request $request)
	{
			$inventory_movements = InventoryMovement::where('move_code','like','%'.$request->search.'%')
					->orWhere('move_number', 'like','%'.$request->search.'%')
					->orderBy('move_id')
					->paginate($this->paginateValue);

			return view('inventory_movements.index', [
					'inventory_movements'=>$inventory_movements,
					'search'=>$request->search
					]);
	}

	public function searchDocument(Request $request)
	{
			$documents = InventoryMovement::where('move_posted',1)
					->where('move_number', 'like','%'.$request->search.'%')
					->orderBy('move_id', 'desc')
					->paginate($this->paginateValue);

			$movement = InventoryMovement::find($request->move_id);

			return view('inventory_movements.master_document',[
				'movement'=>$movement,
				'documents'=>$documents,
				'search'=>$request->search,
				'reason'=>'stock',
				'reload'=>null,
			]);
	}

	public function searchById($id)
	{
			$inventory_movements = InventoryMovement::orderBy('move_posted')
					->where('move_id','=',$id)
					->paginate($this->paginateValue);

			return view('inventory_movements.index', [
					'inventory_movements'=>$inventory_movements
			]);
	}

	public function add(Request $request, $move_id, $product_code)
	{
			$store_code =  Auth::user()->defaultStore($request);
			$movement = InventoryMovement::find($move_id);

			$helper = new StockHelper();

			$inventory = new Inventory();
			$inventory->move_id = $movement->move_id;
			$inventory->move_code = $movement->move_code;
			$inventory->store_code = $movement->store_code;
			$inventory->product_code = $product_code;
			$inventory->inv_book_quantity = $helper->getStockOnHand($product_code, $store_code);
			$inventory->unit_code = 'unit';
			$inventory->inv_subtotal = 0;
			$inventory->save();

			Session::flash('message', 'Record added successfully.');
			return redirect('/product_searches?reason=stock&move_id='.$move_id);
	}

	public function postItem(Request $request)
	{
			$to = $request->to;

			foreach ($request->all() as $id=>$value) {
					switch ($id) {
							case '_token':
									break;
							case 'to':
									break;
							case 'from':
									break;
							default:
									Log::info($id.'->'.$request->to);
									$this->convertItem($id, $request->to);
					}
			}
			return redirect('/inventory_movements/master_item/'.$request->to.'/'.$request->from.'?reason=stock&reload=true');
	}
}
