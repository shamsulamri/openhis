<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Patient;
use Log;
use DB;
use Session;
use App\Tourist;
use App\Gender;
use App\Religion;
use App\Title;
use App\MaritalStatus as Marital;
use App\Nation;
use App\Race;
use App\Occupation;
use App\Registration;
use App\Employer;
use App\Relationship;
use App\State;
use App\PatientFlag;
use App\PatientDependant;
use App\Encounter;

class PatientController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$patients = DB::table('patients')
					->orderBy('patient_id','desc')
					->paginate($this->paginateValue);
			return view('patients.index', [
					'patients'=>$patients,
			]);
	}

	public function create()
	{
			$patient = new Patient();
			return view('patients.create', [
					'patient' => $patient,
					'tourist' => Tourist::all()->sortBy('tourist_name')->lists('tourist_name', 'tourist_code')->prepend('',''),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'religion' => Religion::all()->sortBy('religion_name')->lists('religion_name', 'religion_code')->prepend('',''),
					'title' => Title::all()->sortBy('title_name')->lists('title_name', 'title_code')->prepend('',''),
					'marital' => Marital::all()->sortBy('marital_name')->lists('marital_name', 'marital_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					'race' => Race::all()->sortBy('race_name')->lists('race_name', 'race_code')->prepend('',''),
					'occupation' => Occupation::all()->sortBy('occupation_name')->lists('occupation_name', 'occupation_code')->prepend('',''),
					'registration' => Registration::all()->sortBy('registration_name')->lists('registration_name', 'registration_code')->prepend('',''),
					'relationship' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
					'flag' => PatientFlag::all()->sortBy('flag_name')->lists('flag_name', 'flag_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$patient = new Patient($request->all());
			$valid = $patient->validate($request->all(), "demography");
			if ($valid->passes()) {
					$patient->save();
					$mrn = "MSU".str_pad($patient->patient_id, 6, '0', STR_PAD_LEFT);
					$patient->patient_mrn = $mrn;
					$patient->save();
					Session::flash('message', 'Record successfully created.');
					//return redirect('/patients/'.$patient->patient_id.'/edit?tab=demography');
					return redirect('/patients/'.$patient->patient_id);
			} else {
					return redirect('/patients/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function getDependants($id) 
	{
			$patients = PatientDependant::select('dependant_id as id')
					->where('patient_id','=', $id)->get();
			$patients = $patients->merge(PatientDependant::select('patient_id as id')
					->where('dependant_id','=', $id)->get());
			return $patients;
	}

	public function show($id)
	{
			$patient = Patient::findOrFail($id);
			
			$encounter_active=True;
			$encounter_completed=True;
			$encounter = Encounter::where('patient_id', $id)
							->orderBy('encounter_id')
							->first();

			/*
			if (count($encounter)==0) $encounter_active=False;
			if ($encounter) {
					if ($encounter->admission==null && $encounter->queue==null) $encounter_completed=False;
			}
			
			if (!$encounter_completed) {
					Encounter::find($encounter->encounter_id)->delete();
					$encounter_active=false;
			}
			 */
			
			$patients = $this->getDependants($id);

			for ($i=0;$i<3;$i++) {
					foreach ($patients as $x) {
							$patients = $patients->merge($this->getDependants($x->id));
							Log::info($patients);
					}
			}
			$patients=$patients->except([$id]);
			$patients = Patient::select('patient_name', 'patient_id', 'patient_mrn','patient_phone_home', 'patient_phone_mobile')
					->whereIn('patient_id', $patients->unique())
					->orderBy('patient_name')
					->get();

			return view('patients.view', [
					'patient'=>$patient,
					'patientOption'=>'',
					'patients'=>$patients,
					'encounter'=>$encounter,
					'encounter_active'=>$patient->hasActiveEncounter(),
					]);
	}

	public function edit(Request $request, $id) 
	{
			$patient = Patient::findOrFail($id);
		
			return view('patients.demography', [
					'patient'=>$patient,
					'tourist' => Tourist::all()->sortBy('tourist_name')->lists('tourist_name', 'tourist_code')->prepend('',''),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'religion' => Religion::all()->sortBy('religion_name')->lists('religion_name', 'religion_code')->prepend('',''),
					'title' => Title::all()->sortBy('title_name')->lists('title_name', 'title_code')->prepend('',''),
					'marital' => Marital::all()->sortBy('marital_name')->lists('marital_name', 'marital_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					'race' => Race::all()->sortBy('race_name')->lists('race_name', 'race_code')->prepend('',''),
					'occupation' => Occupation::all()->sortBy('occupation_name')->lists('occupation_name', 'occupation_code')->prepend('',''),
					'registration' => Registration::all()->sortBy('registration_name')->lists('registration_name', 'registration_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					'relationship' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
					'flag' => PatientFlag::all()->sortBy('flag_name')->lists('flag_name', 'flag_code')->prepend('',''),
					'patientOption' => 'demography',
					]);
	}

	public function update(Request $request, $id) 
	{
			$patient = Patient::findOrFail($id);
			$patient->fill($request->input());
			$patient->patient_is_unknown = $request->patient_is_unknown ?: 0;
			
			$valid = $patient->validate($request->all());	
			if ($valid->passes()) {
					$patient->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/patients/'.$id);
			} else {
					return redirect('/patients/'.$id.'/edit')
						->withErrors($valid)
						->withInput();
			}
	}
	
	public function delete($id)
	{
		$patient = Patient::findOrFail($id);
		return view('patients.destroy', [
			'patient'=>$patient
			]);

	}
	public function destroy($id)
	{	
			Patient::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/patients');
	}
	
	public function search(Request $request)
	{
			$patients = DB::table('patients')
					->where('patient_name','like','%'.$request->search.'%')
					->orWhere('patient_mrn', 'like','%'.$request->search.'%')
					->orWhere('patient_new_ic', 'like','%'.$request->search.'%')
					->orderBy('patient_name')
					->paginate($this->paginateValue);

			return view('patients.index', [
					'patients'=>$patients,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$patients = DB::table('patients')
					->where('patient_id','=',$id)
					->paginate($this->paginateValue);

			return view('patients.index', [
					'patients'=>$patients
			]);
	}

	public function dependants($patient_id)
	{
			$patient = Patient::find($patient_id);
			return view('patients.dependant', [
					'patient' => $patient,
			]);

	}

	public function dependantList($id)
	{
			$patient = Patient::findOrFail($id);
			
			$patients = $this->getDependants($id);

			for ($i=0;$i<3;$i++) {
					foreach ($patients as $x) {
							$patients = $patients->merge($this->getDependants($x->id));
							Log::info($patients);
					}
			}
			$patients=$patients->except([$id]);
			$patients = Patient::select('patient_name', 'patient_id', 'patient_mrn','patient_phone_home', 'patient_phone_mobile')
					->whereIn('patient_id', $patients->unique())
					->orderBy('patient_name')
					->get();

			return view('patients.dependant_list', [
					'patient'=>$patient,
					'patients'=>$patients,
					]);
	}
}
