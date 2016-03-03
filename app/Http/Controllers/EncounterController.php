<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Encounter;
use Log;
use DB;
use Session;
use App\Employer;
use App\Patient;
use App\PatientType;
use App\CareOrganisation;
use App\Triage;
use App\Relationship;
use App\EncounterType;
		
class EncounterController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$encounters = DB::table('encounters')
					->join('patients','patients.patient_id','=','encounters.patient_id')
					->join('ref_encounter_types', 'ref_encounter_types.encounter_code','=','encounters.encounter_code')
					->select('encounter_id','encounters.encounter_code', 'patient_name', 'encounters.patient_id', 'encounter_name','encounters.created_at')
					->orderBy('created_at')
					->paginate($this->paginateValue);

			return view('encounters.index', [
					'encounters'=>$encounters
			]);
	}

	public function create(Request $request)
	{
			$patient = new Patient();
			if (empty($request->patient_id)==false) {
				$patient = Patient::findOrFail($request->patient_id);
			}

			$encounter = new Encounter();
			return view('encounters.create', [
					'encounter' => $encounter,
					'employer' => Employer::all()->sortBy('employer_name')->lists('employer_name', 'employer_code')->prepend('',''),
					'patient' => $patient,
					'patient_type' => PatientType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'employer' => Employer::all()->sortBy('employer_name')->lists('employer_name', 'employer_code')->prepend('',''),
					'organisation' => CareOrganisation::all()->sortBy('organisation_name')->lists('organisation_name', 'organisation_code')->prepend('',''),
					'triage' => Triage::all()->sortBy('triage_name')->lists('triage_name', 'triage_code')->prepend('',''),
					'relationship' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
				]);
	}

	public function store(Request $request) 
	{
			$encounter = new Encounter();
			$valid = $encounter->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$encounter = new Encounter($request->all());
					$encounter->encounter_id = $request->encounter_id;
					$encounter->save();
					Session::flash('message', 'Record successfully created.');
					if ($encounter->encounter_code=='outpatient') {
							return redirect('/queues/create?encounter_id='.$encounter->encounter_id);
					} elseif ($encounter->encounter_code=='inpatient') {
							return redirect('/admissions/create?encounter_id='.$encounter->encounter_id);
					} elseif ($encounter->encounter_code=='emergency') {
							if ($encounter->encounter_code=='green') {
								return redirect('/queues/create?encounter_id='.$encounter->encounter_id);
							} else {
								return redirect('/admissions/create?encounter_id='.$encounter->encounter_id);
							}	
					} elseif ($encounter->encounter_code=='daycare') {
							return redirect('/admissions/create?encounter_id='.$encounter->encounter_id);
					} else {
							return redirect('/encounters/id/'.$encounter->encounter_id);
					}
			} else {
					return redirect('/encounters/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$encounter = Encounter::findOrFail($id);
			return view('encounters.edit', [
					'encounter'	=> $encounter,
					'employer' 	=> Employer::all()->sortBy('employer_name')->lists('employer_name', 'employer_code')->prepend('',''),
					'patient'	=> $encounter->patient,
					'patient_type' => PatientType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'employer' => Employer::all()->sortBy('employer_name')->lists('employer_name', 'employer_code')->prepend('',''),
					'organisation' => CareOrganisation::all()->sortBy('organisation_name')->lists('organisation_name', 'organisation_code')->prepend('',''),
					'triage' => Triage::all()->sortBy('triage_name')->lists('triage_name', 'triage_code')->prepend('',''),
					'relationship' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$encounter = Encounter::findOrFail($id);
			$encounter->fill($request->input());

			$valid = $encounter->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$encounter->save();
					Session::flash('message', 'Record successfully updated.');
					if ($encounter->encounter_code=='outpatient') {
							return redirect('/queues/create?encounter_id='.$encounter->encounter_id);
					} elseif ($encounter->encounter_code=='inpatient') {
							return redirect('/admissions/create?encounter_id='.$encounter->encounter_id);
					} elseif ($encounter->encounter_code=='emergency') {
							if ($encounter->triage_code=='green') {
								return redirect('/queues/create?encounter_id='.$encounter->encounter_id);
							} else {
								return redirect('/admissions/create?encounter_id='.$encounter->encounter_id);
							}
					} elseif ($encounter->encounter_code=='daycare') {
							return redirect('/admissions/create?encounter_id='.$encounter->encounter_id);
					} else {
							return redirect('/encounters/id/'.$id);
					}			
			} else {
					return view('encounters.edit', [
							'encounter'=>$encounter,
							'employer' => Employer::all()->sortBy('employer_name')->lists('employer_name', 'employer_code')->prepend('',''),
							'patient_type' => PatientType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$encounter = Encounter::findOrFail($id);
		return view('encounters.destroy', [
			'encounter'=>$encounter
			]);

	}
	public function destroy($id)
	{	
			Encounter::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/encounters');
	}
	
	public function search(Request $request)
	{
			$encounters = DB::table('encounters')
					->where('encounter_code','like','%'.$request->search.'%')
					->orWhere('encounter_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_code')
					->paginate($this->paginateValue);

			return view('encounters.index', [
					'encounters'=>$encounters,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$encounters = DB::table('encounters')
					->where('encounter_id','=',$id)
					->paginate($this->paginateValue);

			return view('encounters.index', [
					'encounters'=>$encounters
			]);
	}
}
