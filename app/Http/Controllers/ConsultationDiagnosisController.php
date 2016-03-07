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

	public function index($consultation_id)
	{
			$consultation_diagnoses = DB::table('consultation_diagnoses')
					->where('consultation_id','=',$consultation_id)
					->orderBy('diagnosis_clinical')
					->paginate($this->paginateValue);

			$consultation = Consultation::findOrFail($consultation_id);
			
			return view('consultation_diagnoses.index', [
					'consultation_diagnoses'=>$consultation_diagnoses,
					'consultation'=>$consultation,
					'tab'=>'diagnosis',
			]);
	}

	public function create($consultation_id)
	{
			$consultation_diagnosis = new ConsultationDiagnosis();
			if (empty($consultation_id)==false) {
					$consultation_diagnosis->consultation_id = $consultation_id;
			}

			$consultation = Consultation::findOrFail($consultation_id);
			
			return view('consultation_diagnoses.create', [
					'consultation_diagnosis' => $consultation_diagnosis,
					'diagnosis_type' => DiagnosisType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'consultation'=>$consultation,
					'tab'=>'diagnosis',
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
					return redirect('/consultation_diagnoses/'.$consultation_diagnosis->consultation_id);
			} else {
					return redirect('/consultation_diagnoses/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$consultation_diagnosis = ConsultationDiagnosis::findOrFail($id);
			$consultation = Consultation::findOrFail($consultation_diagnosis->consultation_id);

			return view('consultation_diagnoses.edit', [
					'consultation_diagnosis'=>$consultation_diagnosis,
					'diagnosis_type' => DiagnosisType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'consultation'=>$consultation,
					'tab'=>'diagnosis',
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
					return redirect('/consultation_diagnoses/'.$consultation_diagnosis->consultation_id);
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
		$consultation_diagnosis = ConsultationDiagnosis::findOrFail($id);
		return view('consultation_diagnoses.destroy', [
			'consultation_diagnosis'=>$consultation_diagnosis
			]);

	}
	public function destroy($id)
	{	
			$consultation_diagnosis = ConsultationDiagnosis::findOrFail($id);
			ConsultationDiagnosis::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultation_diagnoses/'.$consultation_diagnosis->consultation_id);
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
