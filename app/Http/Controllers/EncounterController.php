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
use App\Team;
use App\DojoUtility;
use App\BedBooking;
use App\WardClass;
use App\Appointment;
use App\BedCharge;
use App\BedHelper;
use App\Entitlement;
		
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
			$bed_booking=null;
			$patient = new Patient();
			if (empty($request->patient_id)==false) {
				$patient = Patient::findOrFail($request->patient_id);
			}

			$encounter = new Encounter();


			/*
			$locations = Location::select(DB::raw("concat(location_name, ' (', department_name, ')') as location_name, location_code")
					->leftJoin('departments as b', 'b.department_code', '=', 'queue_locations.department_code')
					->whereNotNull('encounter_code')
					->get();

			$locations = Location::select(DB::raw("concat(location_name, ' (', department_name, ')') as location_name, location_code, encounter_code"))
					->leftJoin('departments as b', 'b.department_code', '=', 'queue_locations.department_code')
					->whereNotNull('encounter_code')
					->orderBy('department_name')
					->orderBy('location_name')
					->get();
			 */

			$locations = Location::selectRaw('location_name, location_code, encounter_code')
					->whereNotNull('encounter_code')
					->orderBy('location_name')
					->get();

			$consultants = user::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('consultant',1)
							->orderby('name')
							->lists('name','id')
							->prepend('','');

			$today = DojoUtility::today();
			$today = DojoUtility::dateWriteFormat($today);
			$preadmissions = BedBooking::selectRaw('count(*) as preadmissions, ward_code, class_code')->where('book_date', $today)->groupBy(['ward_code', 'class_code'])->get();
			

			Bed::where('status_code','04')->update(['status_code'=>'01']);

			foreach($preadmissions as $preadmission) {
				$preadmission_beds = Bed::where('status_code','04')
										->where('ward_code', $preadmission->ward_code)
										->where('class_Code', $preadmission->class_code)
										->count();


				if ($preadmission_beds<$preadmission->preadmissions) {
						$beds = Bed::where('status_code','01')
										->where('ward_code', $preadmission->ward_code)
										->where('class_Code', $preadmission->class_code)
										->limit($preadmission->preadmissions-$preadmission_beds)
										->orderBy('bed_name')
										->get();

						foreach($beds as $bed) {
								$bed->status_code = '04';
								$bed->save();
						}		
				}
			}

			$beds =  Bed::leftJoin('ward_rooms as b', 'b.room_code', '=', 'beds.room_code')
							->where('status_code','=','01')
							->orWhere('status_code','04')
							->orderBy('bed_name')
							->get();

			$wards = Ward::select(DB::raw("ward_name, ward_code"))
							->where('ward_omission', '=', '0')	
							->orderBy('ward_name')
							->lists('ward_name', 'ward_code')
							->prepend('','');

			$ward_classes = Bed::select(DB::raw('beds.ward_code, class_name, beds.class_code,beds.encounter_code,  count(*)'))
					->leftJoin('ward_classes as b', 'b.class_code', '=', 'beds.class_code')
					->groupBy(['ward_code','b.class_code'])
					->get();
					
			$ward_code = null;
			if (!empty($request->book_id)) {
				$bed_booking = BedBooking::find($request->book_id);
				$encounter->encounter_code = $bed_booking->ward->encounter_code;
			}

			$appointment = null;
			if (!empty($request->appointment_id)) {
				$appointment = Appointment::find($request->appointment_id);
				$user = User::where('service_id', $appointment->service_id)->first();
				if ($user) {
						$encounter->location_code = $user->location_code;
				}
				$encounter->encounter_code = 'outpatient';
			}

			$bed_helper = new BedHelper();
			$empty_rooms = $bed_helper->getEmptyRooms();
			return view('encounters.create', [
					'encounter' => $encounter,
					'patient' => $patient,
					'patient_type' => PatientType::all()->sortBy('type_name')->lists('type_name', 'type_code'),
					'sponsor' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
					'sponsors' => Sponsor::all(),
					'entitlement' => Entitlement::all()->sortBy('entitlement_name')->lists('entitlement_name', 'entitlement_code')->prepend('',''),
					'triage' => Triage::all()->sortBy('triage_position')->lists('triage_name', 'triage_code')->prepend('',''),
					'relationship' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'patientOption' => 'encounter',
					'locations' => $locations,
					'consultants' => $consultants,
					'teams' => Team::all()->sortBy('team_name')->lists('team_name', 'team_code')->prepend('',''),
					'wards' => $wards,
					'classes' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'beds' => $beds,
					'referral' => Referral::all()->sortBy('referral_name')->lists('referral_name', 'referral_code')->prepend('',''),
					'admission_type' => AdmissionType::where('admission_code','<>','observe')->orderBy('admission_name')->lists('admission_name', 'admission_code')->prepend('',''),
					'preadmissions'=>$preadmissions,
					'ward_classes'=>$ward_classes,
					'bed_booking'=>$bed_booking,
					'appointment'=>$appointment,
					'empty_rooms'=>$empty_rooms,
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
			$valid=null;
			
			if ($request->encounter_code == 'inpatient') {
					if (empty($request->user_id)) $valid['user_id']='This field is required.';
					if (empty($request->bed_code)) $valid['bed_code']='This field is required.';

					if (!empty($valid)) {
							return redirect('/encounters/create?patient_id='.$request->patient_id)
								->withErrors($valid)
								->withInput();
					} 
			}

			if ($request->encounter_code == 'daycare') {
					if (empty($request->user_id)) $valid['user_id']='This field is required.';

					if (!empty($valid)) {
							return redirect('/encounters/create?patient_id='.$request->patient_id)
								->withErrors($valid)
								->withInput();
					} 

			}

			$encounter = new Encounter($request->all());
			$valid = $encounter->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$encounter->save();

					/** Set patient mrn if empty **/
					$patient = Patient::find($encounter->patient_id);
					if (empty($patient->patient_mrn)) {
							$mrn = "MSU".str_pad($patient->patient_id, 6, '0', STR_PAD_LEFT);
							$patient->patient_mrn = $mrn;
							$patient->save();
							Log::info($patient->patient_mrn);
					}

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
							$bed = Bed::find($request->bed_code);
							$bed->status_code = '03';
							$bed->save();

							$admission = new Admission();
							$admission->bed_code = $request->bed_code;
							$admission->anchor_bed = $request->bed_code;
							$admission->admission_code = $request->admission_code;
							$admission->referral_code = $request->referral_code;
							$admission->user_id = $request->user_id;
							$admission->encounter_id = $request->encounter_id;
							$admission->diet_code='normal';
							$admission->class_code='class_normal';
							$admission->encounter_id = $encounter->encounter_id;
							$admission->block_room = $request->block_room;
							//$admission->team_code = $request->team_code;
							$admission->save();

							//Block other beds in room if room is blocked
							if ($admission->block_room==1) {
								$other_beds = Bed::where('room_code',$bed->room_code)
												->where('bed_code', '<>', $bed->bed_code)
												->get();

								foreach ($other_beds as $other_bed) {
										DB::table('beds')
												->where('bed_code', $other_bed->bed_code)
												->update(['status_code'=>'05']);
								}

							}	

							$bed_movement = new BedMovement();
							$bed_movement->admission_id = $admission->admission_id;
							$bed_movement->encounter_id = $encounter->encounter_id;
							$bed_movement->move_from = $admission->bed_code;
							$bed_movement->move_to = $admission->bed_code;
							$bed_movement->move_date = date('d/m/Y');
							$bed_movement->transaction_code = 'admission';
							$bed_movement->save();

							$bed_charge = new BedCharge();
							$bed_charge->encounter_id = $encounter->encounter_id;
							$bed_charge->bed_code = $admission->bed_code;
							$bed_charge->bed_start = date('d/m/Y');
							$bed_charge->block_room = $request->block_room;
							$bed_charge->save();

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
					'sponsor' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
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
