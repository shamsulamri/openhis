<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ConsultationDiagnosis;
use Log;
use DB;
use Session;
use App\DiagnosisType;

class ConsultationDiagnosisController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$consultation_diagnoses = DB::table('consultation_diagnoses')
					->orderBy('diagnosis_clinical')
					->paginate($this->paginateValue);
			return view('consultation_diagnoses.index', [
					'consultation_diagnoses'=>$consultation_diagnoses
			]);
	}

	public function create(Request $request)
	{
			$consultation_diagnosis = new ConsultationDiagnosis();
			if (empty($request->consultation_id)==false) {
					$consultation_diagnosis->consultation_id = $request->consultation_id;
			}

			return view('consultation_diagnoses.create', [
					'consultation_diagnosis' => $consultation_diagnosis,
					'diagnosis_type' => DiagnosisType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$consultation_diagnosis = new ConsultationDiagnosis();
			$valid = $consultation_diagnosis->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$consultation_diagnosis = new ConsultationDiagnosis($request->all());
					$consultation_diagnosis->id = $request->id;
					$consultation_diagnosis->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/consultation_diagnoses/id/'.$consultation_diagnosis->id);
			} else {
					return redirect('/consultation_diagnoses/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$consultation_diagnosis = ConsultationDiagnosis::findOrFail($id);
			return view('consultation_diagnoses.edit', [
					'consultation_diagnosis'=>$consultation_diagnosis,
					'diagnosis_type' => DiagnosisType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$consultation_diagnosis = ConsultationDiagnosis::findOrFail($id);
			$consultation_diagnosis->fill($request->input());

			$consultation_diagnosis->diagnosis_is_principal = $request->diagnosis_is_principal ?: 0;

			$valid = $consultation_diagnosis->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$consultation_diagnosis->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultation_diagnoses/id/'.$id);
			} else {
					return view('consultation_diagnoses.edit', [
							'consultation_diagnosis'=>$consultation_diagnosis,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$consultation_diagnosis = ConsultationDiagnosis::findOrFail($id);
		return view('consultation_diagnoses.destroy', [
			'consultation_diagnosis'=>$consultation_diagnosis
			]);

	}
	public function destroy($id)
	{	
			ConsultationDiagnosis::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultation_diagnoses');
	}
	
	public function search(Request $request)
	{
			$consultation_diagnoses = DB::table('consultation_diagnoses')
					->where('diagnosis_clinical','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
					->orderBy('diagnosis_clinical')
					->paginate($this->paginateValue);

			return view('consultation_diagnoses.index', [
					'consultation_diagnoses'=>$consultation_diagnoses,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$consultation_diagnoses = DB::table('consultation_diagnoses')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('consultation_diagnoses.index', [
					'consultation_diagnoses'=>$consultation_diagnoses
			]);
	}
}
