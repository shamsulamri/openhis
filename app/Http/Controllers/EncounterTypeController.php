<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\EncounterType;
use Log;
use DB;
use Session;


class EncounterTypeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$encounter_types = DB::table('ref_encounter_types')
					->orderBy('encounter_name')
					->paginate($this->paginateValue);
			return view('encounter_types.index', [
					'encounter_types'=>$encounter_types
			]);
	}

	public function create()
	{
			$encounter_type = new EncounterType();
			return view('encounter_types.create', [
					'encounter_type' => $encounter_type,
				
					]);
	}

	public function store(Request $request) 
	{
			$encounter_type = new EncounterType();
			$valid = $encounter_type->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$encounter_type = new EncounterType($request->all());
					$encounter_type->encounter_code = $request->encounter_code;
					$encounter_type->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/encounter_types/id/'.$encounter_type->encounter_code);
			} else {
					return redirect('/encounter_types/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$encounter_type = EncounterType::findOrFail($id);
			return view('encounter_types.edit', [
					'encounter_type'=>$encounter_type,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$encounter_type = EncounterType::findOrFail($id);
			$encounter_type->fill($request->input());


			$valid = $encounter_type->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$encounter_type->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/encounter_types/id/'.$id);
			} else {
					return view('encounter_types.edit', [
							'encounter_type'=>$encounter_type,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$encounter_type = EncounterType::findOrFail($id);
		return view('encounter_types.destroy', [
			'encounter_type'=>$encounter_type
			]);

	}
	public function destroy($id)
	{	
			EncounterType::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/encounter_types');
	}
	
	public function search(Request $request)
	{
			$encounter_types = DB::table('ref_encounter_types')
					->where('encounter_name','like','%'.$request->search.'%')
					->orWhere('encounter_code', 'like','%'.$request->search.'%')
					->orderBy('encounter_name')
					->paginate($this->paginateValue);

			return view('encounter_types.index', [
					'encounter_types'=>$encounter_types,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$encounter_types = DB::table('ref_encounter_types')
					->where('encounter_code','=',$id)
					->paginate($this->paginateValue);

			return view('encounter_types.index', [
					'encounter_types'=>$encounter_types
			]);
	}
}
