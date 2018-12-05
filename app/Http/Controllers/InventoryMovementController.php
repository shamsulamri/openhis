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

class InventoryMovementController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$inventory_movements = InventoryMovement::orderBy('move_code')
					->paginate($this->paginateValue);

			return view('inventory_movements.index', [
					'inventory_movements'=>$inventory_movements
			]);
	}

	public function show($id)
	{
			$movement = InventoryMovement::find($id);
					
			return view('inventory_movements.show', [
					'movement'=>$movement,
			]);
	}

	public function create()
	{
			$inventory_movement = new InventoryMovement();

			return view('inventory_movements.create', [
					'inventory_movement' => $inventory_movement,
					'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'store_transfer' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$inventory_movement = new InventoryMovement();
			$valid = $inventory_movement->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$inventory_movement = new InventoryMovement($request->all());
					$inventory_movement->user_id = Auth::user()->username;
					$inventory_movement->save();

					Session::flash('message', 'Record successfully created.');
					return redirect('/inventory_movements/id/'.$inventory_movement->move_id);
			} else {
					return redirect('/inventory_movements/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$inventory_movement = InventoryMovement::findOrFail($id);
			return view('inventory_movements.edit', [
					'inventory_movement'=>$inventory_movement,
					'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'store_transfer' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$inventory_movement = InventoryMovement::findOrFail($id);
			$inventory_movement->fill($request->input());

			$inventory_movement->move_close = $request->move_close ?: 0;

			$valid = $inventory_movement->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$inventory_movement->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/inventory_movements/id/'.$id);
			} else {
					return view('inventory_movements.edit', [
							'inventory_movement'=>$inventory_movement,
							'move' => Move::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
							'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
							'store_transfer' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					])
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
			$inventory_movements = DB::table('inventory_movements')
					->where('move_code','like','%'.$request->search.'%')
					->orWhere('move_id', 'like','%'.$request->search.'%')
					->orderBy('move_code')
					->paginate($this->paginateValue);

			return view('inventory_movements.index', [
					'inventory_movements'=>$inventory_movements,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$inventory_movements = DB::table('inventory_movements')
					->where('move_id','=',$id)
					->paginate($this->paginateValue);

			return view('inventory_movements.index', [
					'inventory_movements'=>$inventory_movements
			]);
	}

	public function add($move_id, $product_code)
	{
			$movement = InventoryMovement::find($move_id);

			$inventory = new Inventory();
			$inventory->move_id = $movement->move_id;
			$inventory->move_code = $movement->move_code;
			$inventory->store_code = $movement->store_code;
			$inventory->product_code = $product_code;
			$inventory->inv_quantity = 0;
			$inventory->inv_subtotal = 0;
			$inventory->save();

			Session::flash('message', 'Record added successfully.');
			return redirect('/product_searches?reason='.$movement->move_code.'&move_id='.$move_id);
	}

}
