<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Encounter;
use Log;
use DB;
use Session;
use App\Patient;
use App\PatientType;
use App\Triage;
use App\Relationship;
use App\EncounterType;
use App\Admission;
use App\EncounterHelper;
use App\Sponsor;
use App\QueueLocation as Location;
use App\Ward;
use App\Bed;
use App\User;
use App\Referral;
use App\AdmissionType;
use App\Queue;
use App\BedMovement;

		
class EncounterController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			/*
			$encounters = DB::table('encounters as a')
					->select('a.encounter_id','patient_mrn','a.encounter_code', 'patient_name', 'a.patient_id', 'encounter_name','a.created_at', 'discharge_id')
					->join('patients as c','c.patient_id','=','a.patient_id')
					->join('ref_encounter_types as d', 'd.encounter_code','=','a.encounter_code')
					->leftJoin('discharges as b', 'b.encounter_id','=', 'a.encounter_id')
					->orderBy('created_at','desc')
					->paginate($this->paginateValue);
			 */

			$encounters = Encounter::orderBy('created_at','desc')
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

			$locations = Location::whereNotNull('encounter_code')->get();
			$consultants = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('module_consultation',1)
							->orderBy('name')
							->lists('name','id')
							->prepend('','');

			return view('encounters.create', [
					'encounter' => $encounter,
					'patient' => $patient,
					'patient_type' => PatientType::all()->sortBy('type_name')->lists('type_name', 'type_code'),
					'sponsor' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
					'triage' => Triage::all()->sortBy('triage_position')->lists('triage_name', 'triage_code')->prepend('',''),
					'relationship' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'patientOption' => 'encounter',
					'locations' => $locations,
					'consultants' => $consultants,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'beds' => Bed::get(),
					'referral' => Referral::all()->sortBy('referral_name')->lists('referral_name', 'referral_code')->prepend('',''),
					'admission_type' => AdmissionType::where('admission_code','<>','observe')->orderBy('admission_name')->lists('admission_name', 'admission_code')->prepend('',''),
				]);
	}

	public function getActiveEncounterId($patient_id) 
	{

			$flag=True;
			$encounter = Encounter::where('patient_id', $patient_id)
							->leftjoin('discharges', 'discharges.encounter_id','=','encounters.encounter_id')
							->orderBy('encounters.created_at','desc')
							->first();

			$encounter = Encounter::where('patient_id',$patient_id)->first();
		
			if ($encounter==null) $flag=False;
			if ($encounter) {
					$flag=True;
					if (!empty($encounter->discharge->discharge_id)) {
						$flag=False;
					}
			}

			if ($flag) {
					return $encounter->encounter_id;
			} else {
					return 0;
			}	
	}

	public function store(Request $request)
	{
			$encounter = new Encounter($request->all());
			$valid = $encounter->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$encounter->save();

					$queueFlag = False;
					if ($encounter->encounter_code == 'outpatient') $queueFlag=True;
					if ($encounter->encounter_code == 'emergency' && $encounter->triage_code=='green') $queueFlag=True;
					
					if ($queueFlag) {
							$queue = new Queue();
							$queue->location_code = $request->location_code;
							$queue->encounter_id = $encounter->encounter_id;
							$queue->save();
							Log::info($queue);
							Session::flash('message', 'Record successfully created.');
							return redirect('/queues');
					}

					if (!$queueFlag) {
							$admission = new Admission();
							$admission->bed_code = $request->bed_code;
							$admission->admission_code = $request->admission_code;
							$admission->referral_code = $request->referral_code;
							$admission->user_id = $request->user_id;
							$admission->encounter_id = $request->encounter_id;
							$admission->diet_code='normal';
							$admission->encounter_id = $encounter->encounter_id;
							$admission->save();
							Log::info($admission);

							$bed_movement = new BedMovement();
							$bed_movement->admission_id = $admission->admission_id;
							$bed_movement->encounter_id = $encounter->encounter_id;
							$bed_movement->move_from = $admission->bed_code;
							$bed_movement->move_to = $admission->bed_code;
							$bed_movement->move_date = date('d/m/Y');
							$bed_movement->save();

							Session::flash('message', 'Record successfully created.');
							return redirect('/admissions');
					}

			} else {
					return redirect('/encounters/create?patient_id='.$request->patient_id)
							->withErrors($valid)
							->withInput();
			}

	}

	public function store2(Request $request) 
	{
			$encounter = new Encounter();
			$valid = $encounter->validate($request->all(), $request->_method);
			if ($valid->passes()) {
					$encounter = EncounterHelper::getActiveEncounter($request->patient_id);
					if (empty($encounter)) {
							$encounter = new Encounter($request->all());
							$encounter->encounter_id = $request->encounter_id;
							$encounter->save();

							$patient = Patient::find($encounter->patient_id);

							if ($patient->patient_mrn=='-') {
									$mrn = "MSU".str_pad($patient->patient_id, 6, '0', STR_PAD_LEFT);
									$patient->patient_mrn = $mrn;
									$patient->save();
									Log::info($patient->patient_mrn);
							}

							Session::flash('message', 'Record successfully created.');
					}
					if ($encounter->encounter_code=='outpatient') {
							return redirect('/queues/create?encounter_id='.$encounter->encounter_id);
					} elseif ($encounter->encounter_code=='inpatient' || $encounter->encounter_code=='mortuary') {
							return redirect('/admissions/create?encounter_id='.$encounter->encounter_id);
					} elseif ($encounter->encounter_code=='emergency') {
							if ($encounter->triage_code=='green') {
									return redirect('/queues/create?encounter_id='.$encounter->encounter_id);
							} else {
									$admission = new Admission($request->all());
									$admission->encounter_id = $encounter->encounter_id;
									$admission->admission_code = 'observe';
									$admission->diet_code='normal';
									$admission->save();
									return redirect('/admission_beds?admission_id='.$admission->admission_id);
							}	
					} elseif ($encounter->encounter_code=='daycare') {
							return redirect('/admissions/create?encounter_id='.$encounter->encounter_id);
					} else {
							return redirect('/encounters/id/'.$encounter->encounter_id);
					}
			} else {
					return redirect('/encounters/create?patient_id='.$request->patient_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$encounter = Encounter::findOrFail($id);
			return view('encounters.edit', [
					'encounter'	=> $encounter,
					'patient'	=> $encounter->patient,
					'patient_type' => PatientType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
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
					if ($encounter->encounter_code=='inpatient') {
							$admission = Admission::where('encounter_id',$encounter->encounter_id)->first();
							return redirect('/admissions/'.$admission->admission_id.'/edit');
					} else {
							return redirect('/encounters');
					}
					/*
					if ($encounter->encounter_code=='outpatient') {
							return redirect('/queues/create?encounter_id='.$encounter->encounter_id);
					} elseif ($encounter->encounter_code=='inpatient') {
							$admission = Admission::where('encounter_id',$id)->first();
							if (!empty($admission)) {
								return redirect('/admissions/'.$admission->admission_id.'/edit');
							} else { 
								return redirect('/admissions/create?encounter_id='.$encounter->encounter_id);
							}
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
					 */
			} else {
					return view('encounters.edit', [
							'encounter'=>$encounter,
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
			/*
			$encounters = DB::table('encounters')
					->where('encounter_code','like','%'.$request->search.'%')
					->orWhere('encounter_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_code')
					->paginate($this->paginateValue);
			 */

			$encounters = Encounter::orderBy('encounter_code')
							->where('encounter_code','like','%'.$request->search.'%')
							->orWhere('encounter_id', 'like','%'.$request->search.'%')
							->paginate($this->paginateValue);

			return view('encounters.index', [
					'encounters'=>$encounters,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			/*
			$encounters = DB::table('encounters as a')
					->select('a.encounter_id','patient_mrn','a.encounter_code', 'patient_name', 'a.patient_id', 'encounter_name','a.created_at', 'discharge_id')
					->join('patients as c','c.patient_id','=','a.patient_id')
					->join('ref_encounter_types as d', 'd.encounter_code','=','a.encounter_code')
					->leftJoin('discharges as b', 'b.encounter_id','=', 'a.encounter_id')
					->where('a.encounter_id','=',$id)
					->orderBy('created_at','desc')
					->paginate($this->paginateValue);
			 */

			$encounters = Encounter::orderBy('encounter_code')
								->where('encounter_id','=',$id)
								->paginate($this->paginateValue);

			return view('encounters.index', [
					'encounters'=>$encounters
			]);
	}
}
