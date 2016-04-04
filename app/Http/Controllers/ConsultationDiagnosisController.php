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
use App\Consultation;

class ConsultationDiagnosisController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));
			
			$consultation_diagnoses = DB::table('consultation_diagnoses as a')
					->select('id', 'a.created_at', 'diagnosis_clinical','diagnosis_type')
					->leftjoin('consultations as b','b.consultation_id', '=', 'a.consultation_id')
					->where('encounter_id','=',$consultation->encounter_id)
					->orderBy('a.created_at', 'desc')
					->paginate($this->paginateValue);

			if ($consultation_diagnoses->count()==0) {
					return $this->create();
			} else {
					return view('consultation_diagnoses.index', [
							'consultation_diagnoses'=>$consultation_diagnoses,
							'consultation'=>$consultation,
							'patient'=>$consultation->encounter->patient,
							'tab'=>'diagnosis',
							'consultOption' => 'consultation',
					]);
			}
	}

	public function create()
	{
			$consultation_diagnosis = new ConsultationDiagnosis();
			$consultation_diagnosis->consultation_id = Session::get('consultation_id');
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));
			
			return view('consultation_diagnoses.create', [
					'consultation_diagnosis' => $consultation_diagnosis,
					'diagnosis_type' => DiagnosisType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'diagnosis',
					'consultOption' => 'consultation',
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
					return redirect('/consultation_diagnoses/');
			} else {
					return redirect('/consultation_diagnoses/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$consultation_diagnosis = ConsultationDiagnosis::findOrFail($id);
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			return view('consultation_diagnoses.edit', [
					'consultation_diagnosis'=>$consultation_diagnosis,
					'diagnosis_type' => DiagnosisType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'diagnosis',
					'consultOption' => 'consultation',
					]);
	}

	public function update(Request $request, $id) 
	{
			$consultation_diagnosis = ConsultationDiagnosis::findOrFail($id);
			$consultation_diagnosis->fill($request->input());

			$valid = $consultation_diagnosis->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$consultation_diagnosis->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultation_diagnoses');
			} else {
					return view('consultation_diagnoses.edit', [
							'consultation_diagnosis'=>$consultation_diagnosis,
							'consultation'=>$consultation,
							'tab'=>'diagnosis',
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$consultation = Consultation::findOrFail(Session::get('consultation_id'));
		$consultation_diagnosis = ConsultationDiagnosis::findOrFail($id);
		return view('consultation_diagnoses.destroy', [
				'consultation_diagnosis'=>$consultation_diagnosis,
				'consultation'=>$consultation,
				'patient'=>$consultation->encounter->patient,
				'tab'=>'diagnosis',
				'consultOption' => 'consultation',
			]);

	}
	public function destroy($id)
	{	
			ConsultationDiagnosis::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultation_diagnoses');
	}
}
