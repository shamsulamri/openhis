<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PatientMrn;
use Log;
use DB;
use Session;


class PatientMrnController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$patient_mrns = PatientMrn::orderBy('mrn_id')
					->paginate($this->paginateValue);

			return view('patient_mrns.index', [
					'patient_mrns'=>$patient_mrns
			]);
	}

	public function create()
	{
			$patient_mrn = new PatientMrn();
			return view('patient_mrns.create', [
					'patient_mrn' => $patient_mrn,
				
					]);
	}

	public function store(Request $request) 
	{
			$patient_mrn = new PatientMrn();
			$valid = $patient_mrn->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$patient_mrn = new PatientMrn($request->all());
					$patient_mrn->mrn_id = $request->mrn_id;
					$patient_mrn->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/patient_mrns/id/'.$patient_mrn->mrn_id);
			} else {
					return redirect('/patient_mrns/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$patient_mrn = PatientMrn::findOrFail($id);
			return view('patient_mrns.edit', [
					'patient_mrn'=>$patient_mrn,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$patient_mrn = PatientMrn::findOrFail($id);
			$patient_mrn->fill($request->input());


			$valid = $patient_mrn->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$patient_mrn->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/patient_mrns/id/'.$id);
			} else {
					return view('patient_mrns.edit', [
							'patient_mrn'=>$patient_mrn,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$patient_mrn = PatientMrn::findOrFail($id);
		return view('patient_mrns.destroy', [
			'patient_mrn'=>$patient_mrn
			]);

	}
	public function destroy($id)
	{	
			PatientMrn::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/patient_mrns');
	}
	
	public function search(Request $request)
	{
			$patient_mrns = PatientMrn::where('mrn_id','like','%'.$request->search.'%')
					->orWhere('mrn_id', 'like','%'.$request->search.'%')
					->orderBy('mrn_id')
					->paginate($this->paginateValue);

			return view('patient_mrns.index', [
					'patient_mrns'=>$patient_mrns,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$patient_mrns = PatientMrn::where('mrn_id','=',$id)
					->paginate($this->paginateValue);

			return view('patient_mrns.index', [
					'patient_mrns'=>$patient_mrns
			]);
	}
}
