<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PatientType;
use Log;
use DB;
use Session;


class PatientTypeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$patient_types = DB::table('ref_patient_types')
					->orderBy('type_name')
					->paginate($this->paginateValue);
			return view('patient_types.index', [
					'patient_types'=>$patient_types
			]);
	}

	public function create()
	{
			$patient_type = new PatientType();
			return view('patient_types.create', [
					'patient_type' => $patient_type,
				
					]);
	}

	public function store(Request $request) 
	{
			$patient_type = new PatientType();
			$valid = $patient_type->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$patient_type = new PatientType($request->all());
					$patient_type->type_code = $request->type_code;
					$patient_type->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/patient_types/id/'.$patient_type->type_code);
			} else {
					return redirect('/patient_types/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$patient_type = PatientType::findOrFail($id);
			return view('patient_types.edit', [
					'patient_type'=>$patient_type,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$patient_type = PatientType::findOrFail($id);
			$patient_type->fill($request->input());


			$valid = $patient_type->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$patient_type->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/patient_types/id/'.$id);
			} else {
					return view('patient_types.edit', [
							'patient_type'=>$patient_type,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$patient_type = PatientType::findOrFail($id);
		return view('patient_types.destroy', [
			'patient_type'=>$patient_type
			]);

	}
	public function destroy($id)
	{	
			PatientType::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/patient_types');
	}
	
	public function search(Request $request)
	{
			$patient_types = DB::table('ref_patient_types')
					->where('type_name','like','%'.$request->search.'%')
					->orWhere('type_code', 'like','%'.$request->search.'%')
					->orderBy('type_name')
					->paginate($this->paginateValue);

			return view('patient_types.index', [
					'patient_types'=>$patient_types,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$patient_types = DB::table('ref_patient_types')
					->where('type_code','=',$id)
					->paginate($this->paginateValue);

			return view('patient_types.index', [
					'patient_types'=>$patient_types
			]);
	}
}
