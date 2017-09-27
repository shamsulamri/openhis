<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PatientClassification;
use Log;
use DB;
use Session;


class PatientClassificationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$patient_classifications = DB::table('patient_classifications')
					->orderBy('classification_name')
					->paginate($this->paginateValue);
			return view('patient_classifications.index', [
					'patient_classifications'=>$patient_classifications
			]);
	}

	public function create()
	{
			$patient_classification = new PatientClassification();
			return view('patient_classifications.create', [
					'patient_classification' => $patient_classification,
				
					]);
	}

	public function store(Request $request) 
	{
			$patient_classification = new PatientClassification();
			$valid = $patient_classification->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$patient_classification = new PatientClassification($request->all());
					$patient_classification->classification_code = $request->classification_code;
					$patient_classification->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/patient_classifications/id/'.$patient_classification->classification_code);
			} else {
					return redirect('/patient_classifications/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$patient_classification = PatientClassification::findOrFail($id);
			return view('patient_classifications.edit', [
					'patient_classification'=>$patient_classification,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$patient_classification = PatientClassification::findOrFail($id);
			$patient_classification->fill($request->input());


			$valid = $patient_classification->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$patient_classification->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/patient_classifications/id/'.$id);
			} else {
					return view('patient_classifications.edit', [
							'patient_classification'=>$patient_classification,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$patient_classification = PatientClassification::findOrFail($id);
		return view('patient_classifications.destroy', [
			'patient_classification'=>$patient_classification
			]);

	}
	public function destroy($id)
	{	
			PatientClassification::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/patient_classifications');
	}
	
	public function search(Request $request)
	{
			$patient_classifications = DB::table('patient_classifications')
					->where('classification_name','like','%'.$request->search.'%')
					->orWhere('classification_code', 'like','%'.$request->search.'%')
					->orderBy('classification_name')
					->paginate($this->paginateValue);

			return view('patient_classifications.index', [
					'patient_classifications'=>$patient_classifications,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$patient_classifications = DB::table('patient_classifications')
					->where('classification_code','=',$id)
					->paginate($this->paginateValue);

			return view('patient_classifications.index', [
					'patient_classifications'=>$patient_classifications
			]);
	}
}
