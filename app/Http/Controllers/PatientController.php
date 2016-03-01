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
					->orderBy('patient_name')
					->paginate($this->paginateValue);
			return view('patients.index', [
					'patients'=>$patients
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
					]);
	}

	public function store(Request $request) 
	{
			$patient = new Patient($request->all());
			$valid = $patient->validate($request->all(), "demography");
			if ($valid->passes()) {
					$patient->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/patients/'.$patient->patient_id.'/edit?tab=demography');
			} else {
					return redirect('/patients/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function show($id)
	{
			$patient = Patient::findOrFail($id);
			return view('patients.view', [
					'patient'=>$patient,
					]);
	}

	public function edit(Request $request, $id) 
	{
			$patient = Patient::findOrFail($id);
		
			return view('patients.'.$request->tab, [
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
					]);
	}

	public function update(Request $request, $id) 
	{
			$tab = $request->tab;
			$patient = Patient::findOrFail($id);
			$patient->fill($request->input());
			if ($tab=='demography') {
					$patient->patient_is_pati = $request->patient_is_pati ?: 0;
					$patient->patient_is_royal = $request->patient_is_royal ?: 0;
					$patient->patient_is_vip = $request->patient_is_vip ?: 0;
					$patient->patient_is_unknown = $request->patient_is_unknown ?: 0;
			}
			
			$valid = $patient->validate($request->all(), $tab);	
			if ($valid->passes()) {

					$patient->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/patients/'.$id);
			} else {
					Log::info($request->tab);
					return redirect('/patients/'.$id.'/edit?tab='.$tab)
						->withErrors($valid);			
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
					->orWhere('patient_id', 'like','%'.$request->search.'%')
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
}
