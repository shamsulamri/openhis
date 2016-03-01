<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DeliveryMode;
use Log;
use DB;
use Session;


class DeliveryModeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$delivery_modes = DB::table('ref_delivery_modes')
					->orderBy('delivery_name')
					->paginate($this->paginateValue);
			return view('delivery_modes.index', [
					'delivery_modes'=>$delivery_modes
			]);
	}

	public function create()
	{
			$delivery_mode = new DeliveryMode();
			return view('delivery_modes.create', [
					'delivery_mode' => $delivery_mode,
				
					]);
	}

	public function store(Request $request) 
	{
			$delivery_mode = new DeliveryMode();
			$valid = $delivery_mode->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$delivery_mode = new DeliveryMode($request->all());
					$delivery_mode->delivery_code = $request->delivery_code;
					$delivery_mode->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/delivery_modes/id/'.$delivery_mode->delivery_code);
			} else {
					return redirect('/delivery_modes/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$delivery_mode = DeliveryMode::findOrFail($id);
			return view('delivery_modes.edit', [
					'delivery_mode'=>$delivery_mode,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$delivery_mode = DeliveryMode::findOrFail($id);
			$delivery_mode->fill($request->input());


			$valid = $delivery_mode->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$delivery_mode->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/delivery_modes/id/'.$id);
			} else {
					return view('delivery_modes.edit', [
							'delivery_mode'=>$delivery_mode,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$delivery_mode = DeliveryMode::findOrFail($id);
		return view('delivery_modes.destroy', [
			'delivery_mode'=>$delivery_mode
			]);

	}
	public function destroy($id)
	{	
			DeliveryMode::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/delivery_modes');
	}
	
	public function search(Request $request)
	{
			$delivery_modes = DB::table('ref_delivery_modes')
					->where('delivery_name','like','%'.$request->search.'%')
					->orWhere('delivery_code', 'like','%'.$request->search.'%')
					->orderBy('delivery_name')
					->paginate($this->paginateValue);

			return view('delivery_modes.index', [
					'delivery_modes'=>$delivery_modes,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$delivery_modes = DB::table('ref_delivery_modes')
					->where('delivery_code','=',$id)
					->paginate($this->paginateValue);

			return view('delivery_modes.index', [
					'delivery_modes'=>$delivery_modes
			]);
	}
}
