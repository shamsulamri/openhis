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
					->select('id', 'a.created_at', 'diagnosis_clinical','diagnosis_type','diagnosis_is_principal')
					->leftjoin('consultations as b','b.consultation_id', '=', 'a.consultation_id')
					->leftjoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftjoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
					->where('d.patient_id', $consultation->encounter->patient->patient_id)
					->orderBy('c.encounter_id', 'desc')
					->orderBy('diagnosis_is_principal', 'desc')
					->paginate($this->paginateValue);

					//->where('encounter_id','=',$consultation->encounter_id)
			$has_principal = ConsultationDiagnosis::where('diagnosis_is_principal',1)
								->where('consultation_id', Session::get('consultation_id'))
								->first()?1:0;


			if ($consultation_diagnoses->count()==0) {
					return $this->create();
			} else {
					return view('consultation_diagnoses.index', [
							'consultation_diagnoses'=>$consultation_diagnoses,
							'consultation'=>$consultation,
							'patient'=>$consultation->encounter->patient,
							'tab'=>'diagnosis',
							'consultOption' => 'consultation',
							'has_principal'=>$has_principal,
					]);
			}
	}

	public function create()
	{
			$consultation_diagnosis = new ConsultationDiagnosis();
			$consultation_diagnosis->consultation_id = Session::get('consultation_id');
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			$diagnoses = DB::table('consultation_diagnoses as a')
					->select('id', 'a.created_at', 'diagnosis_clinical','diagnosis_type','diagnosis_is_principal')
					->leftjoin('consultations as b','b.consultation_id', '=', 'a.consultation_id')
					->where('encounter_id','=',$consultation->encounter_id);

			if ($diagnoses->count()==0) {
					$consultation_diagnosis->diagnosis_is_principal=1;
			}
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
			if ($request->ajax()) {

				$consultation_diagnosis = new ConsultationDiagnosis();
				$consultation_diagnosis->consultation_id = $request->id;
				$consultation_diagnosis->diagnosis_clinical = $request->diagnosis_clinical;
				$consultation_diagnosis->diagnosis_is_principal = $this->hasPrincipal();
				$consultation_diagnosis->save();

				return $this->generateHTML();

			} else {
				$consultation_diagnosis = new ConsultationDiagnosis();
				$valid = $consultation_diagnosis->validate($request->all(), $request->_method);

				if ($valid->passes()) {
						$consultation_diagnosis = new ConsultationDiagnosis($request->all());
						$consultation_diagnosis->id = $request->id;
						if ($consultation_diagnosis->diagnosis_is_principal) {
								$this->changeAllDiagnosisToSecondary();
						}
						$consultation_diagnosis->save();
						Session::flash('message', 'Record successfully created.');
						return redirect('/consultation_diagnoses');
				} else {
						return redirect('/consultation_diagnoses/create')
						->withErrors($valid)
						->withInput();
				}
			}
	}

	public function getDiagnoses(Request $request)
	{
			if ($request->ajax()) {
					return $this->generateHTML();
			}

	}
	private function hasPrincipal()
	{
				$consultation = Consultation::findOrFail(Session::get('consultation_id'));
					
				$principal = DB::table('consultation_diagnoses as a')
							->select('id', 'a.created_at', 'diagnosis_clinical','diagnosis_type','diagnosis_is_principal')
							->leftjoin('consultations as b','b.consultation_id', '=', 'a.consultation_id')
							->where('encounter_id','=',$consultation->encounter_id)
							->where('diagnosis_is_principal','=',1)
							->count();

				if ($principal) {
						return 0;
				} else {
						return 1;
				}

	}	

	private function generateHTML()
	{
				$consultation = Consultation::findOrFail(Session::get('consultation_id'));
					
				$diagnoses = DB::table('consultation_diagnoses as a')
							->select('id', 'a.created_at', 'diagnosis_clinical','diagnosis_type','diagnosis_is_principal')
							->leftjoin('consultations as b','b.consultation_id', '=', 'a.consultation_id')
							->where('encounter_id','=',$consultation->encounter_id)
							->orderBy('diagnosis_is_principal', 'desc')
							->orderBy('a.created_at')
							->get();

				$html='<table class="table table-hover">';
				$principal='';

				foreach($diagnoses as $diagnosis) {

						if ($diagnosis->diagnosis_is_principal==1) {
								$principal = "
										<div class='label label-primary' title='Princiapl Diagnosis'>
										1°	
										</div>
								";
						} else {
								$principal = "
										<div class='label label-default' title='Secondary Diagnosis'>
										2°	
										</div>
								";
						}

						$html = $html."
							<tr>
									<td style='vertical-align:middle' width='5%'>".$principal."</td>
									<td style='vertical-align:middle'>".$diagnosis->diagnosis_clinical."</td>
									<td class='col-xs-3'>
									</td>
									<td align='right'>
										<a class='btn btn-danger btn-sm' href='javascript:removeDiagnosis(". $diagnosis->id.")'><span class='glyphicon glyphicon-trash'></span></a>
									</td>
							</tr>
						";
				}
				$html = $html."</table>";
				if (count($diagnoses)==0) $html = '';
				return $html;

	}

	public function store_backup(Request $request) 
	{
			$consultation_diagnosis = new ConsultationDiagnosis();
			$valid = $consultation_diagnosis->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$consultation_diagnosis = new ConsultationDiagnosis($request->all());
					$consultation_diagnosis->id = $request->id;
					if ($consultation_diagnosis->diagnosis_is_principal) {
							$this->changeAllDiagnosisToSecondary();
					}
					$consultation_diagnosis->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/consultation_diagnoses');
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

			$principal_changed = false;
			if ($request->diagnosis_is_principal==1 & $consultation_diagnosis->diagnosis_is_principal==0) {
				$principal_changed = true;
			}
			$consultation_diagnosis->fill($request->input());

			$valid = $consultation_diagnosis->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					if ($principal_changed) {
							$this->changeAllDiagnosisToSecondary();
					}
					$consultation_diagnosis->diagnosis_is_principal = $request->diagnosis_is_principal ?: 0;
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
	
	public function changeAllDiagnosisToSecondary() 
	{
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));
			$diagnoses = DB::table('consultation_diagnoses as a')
					->select('id')
					->leftjoin('consultations as b','b.consultation_id', '=', 'a.consultation_id')
					->where('encounter_id','=',$consultation->encounter_id)
					->get();

			foreach ($diagnoses as $diagnosis) {
					DB::table('consultation_diagnoses')
							->where('id',$diagnosis->id)
							->update(['diagnosis_is_principal'=>'0']);
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

	public function drop(Request $request)
	{	
			ConsultationDiagnosis::find($request->diagnosis_id)->delete();
			return $this->generateHTML();
	}

}
