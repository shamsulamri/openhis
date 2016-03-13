<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DischargeType;
use Log;
use DB;
use Session;


class DischargeTypeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$discharge_types = DB::table('ref_discharge_types')
					->orderBy('type_name')
					->paginate($this->paginateValue);
			return view('discharge_types.index', [
					'discharge_types'=>$discharge_types
			]);
	}

	public function create()
	{
			$discharge_type = new DischargeType();
			return view('discharge_types.create', [
					'discharge_type' => $discharge_type,
				
					]);
	}

	public function store(Request $request) 
	{
			$discharge_type = new DischargeType();
			$valid = $discharge_type->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$discharge_type = new DischargeType($request->all());
					$discharge_type->type_code = $request->type_code;
					$discharge_type->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/discharge_types/id/'.$discharge_type->type_code);
			} else {
					return redirect('/discharge_types/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$discharge_type = DischargeType::findOrFail($id);
			return view('discharge_types.edit', [
					'discharge_type'=>$discharge_type,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$discharge_type = DischargeType::findOrFail($id);
			$discharge_type->fill($request->input());


			$valid = $discharge_type->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$discharge_type->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/discharge_types/id/'.$id);
			} else {
					return view('discharge_types.edit', [
							'discharge_type'=>$discharge_type,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$discharge_type = DischargeType::findOrFail($id);
		return view('discharge_types.destroy', [
			'discharge_type'=>$discharge_type
			]);

	}
	public function destroy($id)
	{	
			DischargeType::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/discharge_types');
	}
	
	public function search(Request $request)
	{
			$discharge_types = DB::table('ref_discharge_types')
					->where('type_name','like','%'.$request->search.'%')
					->orWhere('type_code', 'like','%'.$request->search.'%')
					->orderBy('type_name')
					->paginate($this->paginateValue);

			return view('discharge_types.index', [
					'discharge_types'=>$discharge_types,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$discharge_types = DB::table('ref_discharge_types')
					->where('type_code','=',$id)
					->paginate($this->paginateValue);

			return view('discharge_types.index', [
					'discharge_types'=>$discharge_types
			]);
	}
}
