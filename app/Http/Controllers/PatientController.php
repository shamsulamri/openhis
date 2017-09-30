<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Patient;
use Log;
use DB;
use Session;
use App\Gender;
use App\Religion;
use App\Title;
use App\MaritalStatus as Marital;
use App\Nation;
use App\Race;
use App\Occupation;
use App\Relationship;
use App\State;
use App\City;
use App\PatientFlag;
use App\EncounterHelper;
use App\Postcode;
use Carbon\Carbon;
use App\AMQPHelper as Amqp;
use App\Encounter;
use App\Admission;
use App\Appointment;
use App\BedBooking;
use App\Bed;
use App\Consultation;
use App\Dependant;
use App\MedicalAlert;
use App\FormValue;
use App\Newborn;
use App\PatientDependant;
use App\Refund;

class PatientController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{

			//Amqp::pushMessage('lab','This is a new message');

			$patients = Patient::orderBy('patient_id','desc')
					->paginate($this->paginateValue);
			/**
					->where('patient_mrn','=','999')
					->paginate($this->paginateValue);
			**/
			return view('patients.index', [
					'patients'=>$patients,
			]);
	}

	public function create()
	{

			$patient = new Patient();
			$patient->patient_is_unknown=0;
			$patient->patient_cur_country='my';
			$patient->patient_per_country='my';

			return view('patients.create', [
					'patient' => $patient,
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'religion' => Religion::all()->sortBy('religion_name')->lists('religion_name', 'religion_code')->prepend('',''),
					'title' => Title::all()->sortBy('title_name')->lists('title_name', 'title_code')->prepend('',''),
					'marital' => Marital::all()->sortBy('marital_name')->lists('marital_name', 'marital_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					'race' => Race::all()->sortBy('race_name')->lists('race_name', 'race_code')->prepend('',''),
					'occupation' => Occupation::all()->sortBy('occupation_name')->lists('occupation_name', 'occupation_code')->prepend('',''),
					'relationship' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
					'city' => City::all()->sortBy('city_name')->lists('city_name', 'city_code')->prepend('',''),
					'flag' => PatientFlag::all()->sortBy('flag_name')->lists('flag_name', 'flag_code')->prepend('',''),
					'postcodes'=>Postcode::all(),
			]);
	}

	public function store(Request $request) 
	{
			$patient = new Patient($request->all());
			//$valid = $patient->validate($request->all(), "demography");
			$valid = $patient->validate($request->all(), $request->_method);	
			if ($valid->passes()) {
					$patient->save();
					$this->saveImage($patient,$request->file('file'));
					Session::flash('message', 'Record successfully created.');
					return redirect('/patients/'.$patient->patient_id.'/edit');
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

			return view('patients.view', [
					'patient'=>$patient,
					'patientOption'=>'',
					'encounter'=>$patient->hasActiveEncounter(),
					]);
	}

	public function json($id)
	{
			$patient = Patient::findOrFail($id);
			return $patient;
	}

	public function edit(Request $request, $id) 
	{
			$patient = Patient::findOrFail($id);
		
			return view('patients.demography', [
					'patient'=>$patient,
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'religion' => Religion::all()->sortBy('religion_name')->lists('religion_name', 'religion_code')->prepend('',''),
					'title' => Title::all()->sortBy('title_name')->lists('title_name', 'title_code')->prepend('',''),
					'marital' => Marital::all()->sortBy('marital_name')->lists('marital_name', 'marital_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					'race' => Race::all()->sortBy('race_name')->lists('race_name', 'race_code')->prepend('',''),
					'occupation' => Occupation::all()->sortBy('occupation_name')->lists('occupation_name', 'occupation_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					'relationship' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
					'flag' => PatientFlag::all()->sortBy('flag_name')->lists('flag_name', 'flag_code')->prepend('',''),
					'patientOption' => 'demography',
					'postcodes'=>Postcode::all(),
					'city' => City::all()->sortBy('city_name')->lists('city_name', 'city_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$patient = Patient::findOrFail($id);
			$patient->fill($request->input());
			$patient->patient_is_unknown = $request->patient_is_unknown ?: 0;
			
			$valid = $patient->validate($request->all(), $request->_method);	
			if ($valid->passes()) {
					$this->saveImage($patient,$request->file('file'));
					$patient->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/patients/'.$id.'/edit');
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
			$search = $request->search;
			$search = str_replace('-','',$search);
			$patients = Patient::where('patient_name','like','%'.$search.'%')
					->orWhere('patient_mrn', 'like','%'.$search.'%')
					->orWhere('patient_new_ic', 'like','%'.$search.'%')
					->orWhere('patient_old_ic', 'like','%'.$search.'%')
					->orWhere('patient_birth_certificate', 'like','%'.$search.'%')
					->orWhere('patient_passport', 'like','%'.$search.'%')
					->orWhere('patient_police_id', 'like','%'.$search.'%')
					->orWhere('patient_military_id', 'like','%'.$search.'%')
					->orderBy('patient_name')
					->paginate($this->paginateValue);

			return view('patients.index', [
					'patients'=>$patients,
					'search'=>$search,
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
					}
			}
			$patients=$patients->except([$id]);
			$patients = Patient::select('patient_name', 'patient_id', 'patient_mrn','patient_phone_home', 'patient_phone_mobile')
					->whereIn('patient_id', $patients->unique())
					->orderBy('patient_name')
					->get();

			if (count($patients)==0) {
					return redirect('/patients/dependants/'.$id);
			}
			return view('patients.dependant_list', [
					'patient'=>$patient,
					'patients'=>$patients,
]);
	}

	public function saveImage($patient, $file) {
			Log::info($file);
			if ($file) {
					$filename = $patient->patient_mrn.'/'.$patient->patient_mrn;
					Storage::disk('local')->put($filename, File::get($file));
			}
	}

	public function getImage($id)
	{
			$file = Storage::disk('local')->get($id.'/'.$id);
			ob_end_clean();
			return new Response($file, 200);
	}

	public function enquiry(Request $request)
	{
			$patient = null;
			if (!empty($request->search)) {
					$search = $request->search;
					$search = str_replace('-','',$search);
					$patient = Patient::where('patient_mrn', 'like','%'.$search.'%')->first();
			}

			return view('patients.enquiry', [
					'patient'=>$patient,
					'search'=>$request->search,
					]);
	}

	public function merge(Request $request, $id)
	{
			$patient = Patient::find($id);
			$duplicate_patient = Patient::where('patient_mrn',$request->duplicate_id)->first();

			if ($request->target_id) {
					$this->mergeRecord($patient->patient_id, $request->target_id);
					Session::flash('message', 'Record successfully merged.');
					return redirect('/patient/merge/'.$patient->patient_id);
			}
			return view('patients.merge', [
					'patient'=>$patient,
					'duplicate_patient'=>$duplicate_patient,
					'duplicate_id'=>$request->duplicate_id,
			]);

	}


	public function mergeRecord($origin_id, $duplicate_id)
	{
		Encounter::where('patient_id', $duplicate_id)->update(['patient_id'=>$origin_id]);
		Appointment::where('patient_id', $duplicate_id)->update(['patient_id'=>$origin_id]);
		BedBooking::where('patient_id', $duplicate_id)->update(['patient_id'=>$origin_id]);
		Consultation::where('patient_id', $duplicate_id)->update(['patient_id'=>$origin_id]);
		MedicalAlert::where('patient_id', $duplicate_id)->update(['patient_id'=>$origin_id]);
		FormValue::where('patient_id', $duplicate_id)->update(['patient_id'=>$origin_id]);
		Newborn::where('patient_id', $duplicate_id)->update(['patient_id'=>$origin_id]);
		PatientDependant::where('patient_id', $duplicate_id)->update(['patient_id'=>$origin_id]);
		Refund::where('patient_id', $duplicate_id)->update(['patient_id'=>$origin_id]);

		$this->destroy($duplicate_id);
	}
}
