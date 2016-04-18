<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PatientFlag;
use Log;
use DB;
use Session;


class PatientFlagController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$patient_flags = DB::table('ref_patient_flags')
					->orderBy('flag_name')
					->paginate($this->paginateValue);
			return view('patient_flags.index', [
					'patient_flags'=>$patient_flags
			]);
	}

	public function create()
	{
			$patient_flag = new PatientFlag();
			return view('patient_flags.create', [
					'patient_flag' => $patient_flag,
				
					]);
	}

	public function store(Request $request) 
	{
			$patient_flag = new PatientFlag();
			$valid = $patient_flag->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$patient_flag = new PatientFlag($request->all());
					$patient_flag->flag_code = $request->flag_code;
					$patient_flag->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/patient_flags/id/'.$patient_flag->flag_code);
			} else {
					return redirect('/patient_flags/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$patient_flag = PatientFlag::findOrFail($id);
			return view('patient_flags.edit', [
					'patient_flag'=>$patient_flag,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$patient_flag = PatientFlag::findOrFail($id);
			$patient_flag->fill($request->input());


			$valid = $patient_flag->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$patient_flag->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/patient_flags/id/'.$id);
			} else {
					return view('patient_flags.edit', [
							'patient_flag'=>$patient_flag,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$patient_flag = PatientFlag::findOrFail($id);
		return view('patient_flags.destroy', [
			'patient_flag'=>$patient_flag
			]);

	}
	public function destroy($id)
	{	
			PatientFlag::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/patient_flags');
	}
	
	public function search(Request $request)
	{
			$patient_flags = DB::table('ref_patient_flags')
					->where('flag_name','like','%'.$request->search.'%')
					->orWhere('flag_code', 'like','%'.$request->search.'%')
					->orderBy('flag_name')
					->paginate($this->paginateValue);

			return view('patient_flags.index', [
					'patient_flags'=>$patient_flags,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$patient_flags = DB::table('ref_patient_flags')
					->where('flag_code','=',$id)
					->paginate($this->paginateValue);

			return view('patient_flags.index', [
					'patient_flags'=>$patient_flags
			]);
	}
}
