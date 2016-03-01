<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StockMovement;
use Log;
use DB;
use Session;


class StockMovementController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stock_movements = DB::table('stock_movements')
					->orderBy('move_name')
					->paginate($this->paginateValue);
			return view('stock_movements.index', [
					'stock_movements'=>$stock_movements
			]);
	}

	public function create()
	{
			$stock_movement = new StockMovement();
			return view('stock_movements.create', [
					'stock_movement' => $stock_movement,
				
					]);
	}

	public function store(Request $request) 
	{
			$stock_movement = new StockMovement();
			$valid = $stock_movement->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock_movement = new StockMovement($request->all());
					$stock_movement->move_code = $request->move_code;
					$stock_movement->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_movements/id/'.$stock_movement->move_code);
			} else {
					return redirect('/stock_movements/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$stock_movement = StockMovement::findOrFail($id);
			return view('stock_movements.edit', [
					'stock_movement'=>$stock_movement,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock_movement = StockMovement::findOrFail($id);
			$stock_movement->fill($request->input());


			$valid = $stock_movement->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$stock_movement->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stock_movements/id/'.$id);
			} else {
					return view('stock_movements.edit', [
							'stock_movement'=>$stock_movement,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$stock_movement = StockMovement::findOrFail($id);
		return view('stock_movements.destroy', [
			'stock_movement'=>$stock_movement
			]);

	}
	public function destroy($id)
	{	
			StockMovement::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/stock_movements');
	}
	
	public function search(Request $request)
	{
			$stock_movements = DB::table('stock_movements')
					->where('move_name','like','%'.$request->search.'%')
					->orWhere('move_code', 'like','%'.$request->search.'%')
					->orderBy('move_name')
					->paginate($this->paginateValue);

			return view('stock_movements.index', [
					'stock_movements'=>$stock_movements,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$stock_movements = DB::table('stock_movements')
					->where('move_code','=',$id)
					->paginate($this->paginateValue);

			return view('stock_movements.index', [
					'stock_movements'=>$stock_movements
			]);
	}
}
