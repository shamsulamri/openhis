<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StockReceive;
use Log;
use DB;
use Session;


class StockReceiveController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stock_receives = DB::table('stock_receives')
					->orderBy('purchase_id')
					->paginate($this->paginateValue);
			return view('stock_receives.index', [
					'stock_receives'=>$stock_receives
			]);
	}

	public function create()
	{
			$stock_receive = new StockReceive();
			return view('stock_receives.create', [
					'stock_receive' => $stock_receive,
				
					]);
	}

	public function store(Request $request) 
	{
			$stock_receive = new StockReceive();
			$valid = $stock_receive->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock_receive = new StockReceive($request->all());
					$stock_receive->receive_id = $request->receive_id;
					$stock_receive->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_receives/id/'.$stock_receive->receive_id);
			} else {
					return redirect('/stock_receives/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$stock_receive = StockReceive::findOrFail($id);
			return view('stock_receives.edit', [
					'stock_receive'=>$stock_receive,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock_receive = StockReceive::findOrFail($id);
			$stock_receive->fill($request->input());


			$valid = $stock_receive->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$stock_receive->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stock_receives/id/'.$id);
			} else {
					return view('stock_receives.edit', [
							'stock_receive'=>$stock_receive,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$stock_receive = StockReceive::findOrFail($id);
		return view('stock_receives.destroy', [
			'stock_receive'=>$stock_receive
			]);

	}
	public function destroy($id)
	{	
			StockReceive::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/stock_receives');
	}
	
	public function search(Request $request)
	{
			$stock_receives = DB::table('stock_receives')
					->where('purchase_id','like','%'.$request->search.'%')
					->orWhere('receive_id', 'like','%'.$request->search.'%')
					->orderBy('purchase_id')
					->paginate($this->paginateValue);

			return view('stock_receives.index', [
					'stock_receives'=>$stock_receives,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$stock_receives = DB::table('stock_receives')
					->where('receive_id','=',$id)
					->paginate($this->paginateValue);

			return view('stock_receives.index', [
					'stock_receives'=>$stock_receives
			]);
	}
}
