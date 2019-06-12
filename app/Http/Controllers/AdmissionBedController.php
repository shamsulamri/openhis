<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AdmissionBed;
use Log;
use DB;
use Session;
use App\Encounter;
use App\WardClass;
use App\Ward;
use App\Room;
use App\Status;
use App\Gender;
use App\Department;
use App\Admission;
use App\BedMovement;
use App\BedBooking;
use App\Bed;
use App\BedCharge;

class AdmissionBedController extends Controller
{
	public $paginateValue=25;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$setWard = $request->cookie('ward');
			$current_ward = $request->cookie('ward');
			$admission = NULL;
			$patient = NULL;
			$flag=$request->flag;
			$encounter = NULL;
			$ward_class = $request->ward_class;
			$book_id = NULL;
			$book= NULL;
			$current_bed= NULL;
			if (!empty($request->admission_id)) {
					$admission = Admission::find($request->admission_id);
					$patient = $admission->encounter->patient;
					$encounter= Encounter::find($admission->encounter_id);

					$current_bed = $this->getCurrentBed($admission->encounter_id);

					if ($current_bed) {
							$ward_code = $current_bed->bed->ward_code;
					}

					if ($encounter->encounter_code =='emergency') $ward_code = 'observation';
			}

			if (!empty($request->book_id)) {
					$book_id = $request->book_id;
					$book = BedBooking::find($book_id);
					$ward_class=$book->class_code;
					$ward_code = $book->ward_code;
			}


			$admission_beds = DB::table('beds as a')
					->select(['block_room', 'a.status_code', 'b.admission_id','a.bed_code','room_name', 'bed_name','patient_name','a.ward_code', 'ward_name', 'a.class_code', 'class_name','c.patient_id'])
					->leftJoin('admissions as b', 'b.bed_code', '=', 'a.bed_code')
					->leftJoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftJoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
					->leftJoin('discharges as e', 'e.encounter_id', '=', 'c.encounter_id')
					->leftJoin('ward_classes as f', 'f.class_code', '=', 'a.class_code')
					->leftJoin('wards as g', 'g.ward_code', '=', 'a.ward_code')
					->leftJoin('ward_rooms as h', 'h.room_code', '=', 'a.room_code')
					->whereNull('discharge_id')
					->where('a.ward_code','like','%'.$ward_code.'%')
					->where('a.class_code','like','%'.$ward_class.'%')
					->where('g.encounter_code',$encounter->encounter_code)
					->orderBy('room_name')
					->orderBy('bed_name')
					->orderBy('ward_name')
					->paginate($this->paginateValue);

			/*
					$wards2 = Ward::where('encounter_code','=', $encounter->encounter_code)
								->orWhere('encounter_code','=','daycare')
								->orderBy('ward_name')->get(),
					'wards' => Ward::where('encounter_code','=', $encounter->encounter_code)->orderBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
			 */
			return view('admission_beds.index', [
					'admission_beds'=>$admission_beds,
					'wards2' => Ward::where('ward_code', '<>', 'mortuary')->where('ward_code', '<>', 'observation')->orderBy('ward_name')->get(),
					'ward_code'=>$ward_code,
					'current_ward'=>$current_ward,
					'ward_class' => $ward_class,
					'admission' => $admission,
					'patient' => $patient,
					'flag' => $flag,
					'encounter' => $encounter,
					'book_id' => $book_id,
					'book' => $book,
					'move' => $request->move,
					'ward_classes' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'current_bed' => $current_bed,
			]);
	}

	public function getCurrentBed($encounter_id) 
	{
			$bed = BedMovement::where('encounter_id',$encounter_id)->orderBy('move_id','desc')->first();
			return $bed;
	}

	public function create()
	{
			$admission_bed = new AdmissionBed();
			return view('admission_beds.create', [
					'admission_bed' => $admission_bed,
					'encounter' => Encounter::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'room' => Room::all()->sortBy('room_name')->lists('room_name', 'room_code')->prepend('',''),
					'status' => Status::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$admission_bed = new AdmissionBed();
			$admissionsvalid = $admission_bed->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$admission_bed = new AdmissionBed($request->all());
					$admission_bed->bed_code = $request->bed_code;
					$admission_bed->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/admission_beds/id/'.$admission_bed->bed_code);
			} else {
					return redirect('/admission_beds/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$admission_bed = AdmissionBed::findOrFail($id);
			return view('admission_beds.edit', [
					'admission_bed'=>$admission_bed,
					'encounter' => Encounter::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'room' => Room::all()->sortBy('room_name')->lists('room_name', 'room_code')->prepend('',''),
					'status' => Status::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$admission_bed = AdmissionBed::findOrFail($id);
			$admission_bed->fill($request->input());

			$valid = $admission_bed->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$admission_bed->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/admission_beds/id/'.$id);
			} else {
					return view('admission_beds.edit', [
							'admission_bed'=>$admission_bed,
					'encounter' => Encounter::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'room' => Room::all()->sortBy('room_name')->lists('room_name', 'room_code')->prepend('',''),
					'status' => Status::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$admission_bed = AdmissionBed::findOrFail($id);
		return view('admission_beds.destroy', [
			'admission_bed'=>$admission_bed
			]);

	}
	public function destroy($id)
	{	
			AdmissionBed::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/admission_beds');
	}
	
	public function search(Request $request)
	{
			$admission = NULL;
			$patient = NULL;
			$book = NULL;
			$book_id = NULL;
			$flag=$request->flag;

			if (!empty($request->admission_id)) {
					$admission = Admission::find($request->admission_id);
					$patient = $admission->encounter->patient;
					$encounter= Encounter::find($admission->encounter_id);
			}

			$admission_beds = DB::table('beds as a')
					->select(['b.admission_id','a.bed_code','bed_name','room_name', 'patient_name','a.ward_code', 'ward_name','class_name', 'a.class_code','c.patient_id'])
					->leftJoin('admissions as b', 'b.bed_code', '=', 'a.bed_code')
					->leftJoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftJoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
					->leftJoin('discharges as e', 'e.encounter_id', '=', 'c.encounter_id')
					->leftJoin('ward_classes as f', 'f.class_code', '=', 'a.class_code')
					->leftJoin('wards as g', 'g.ward_code', '=', 'a.ward_code')
					->leftJoin('ward_rooms as h', 'h.room_code', '=', 'a.room_code')
					->where('a.class_code','like','%'.$request->ward_class.'%')
					->where('a.ward_code','like','%'.$request->ward_code.'%')
					->where('a.encounter_code','=', $encounter->encounter_code)
					->whereNull('discharge_id')
					->orderBy('class_name')
					->orderBy('bed_name')
					->orderBy('ward_name')
					->orderBy('class_name')
					->paginate($this->paginateValue);

			if (!empty($request->book_id)) {
					$book_id = $request->book_id;
					$book = BedBooking::find($book_id);
			}

			return view('admission_beds.index', [
					'admission_beds'=>$admission_beds,
					'wards' => Ward::where('encounter_code','=', $encounter->encounter_code)->orderBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward_code'=>$request->ward_code,
					'ward_class'=>$request->ward_class,
					'admission' => $admission,
					'patient' => $patient,
					'encounter' => $encounter,
					'flag' => $flag,
					'ward_classes' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'book' => $book,
					'book_id' => $book_id,
					]);
	}

	public function searchById(Request $request, $id, $ward_code)
	{
			$admission = NULL;
			$patient = NULL;
			$book = NULL;
			$book_id = NULL;
			$flag=$request->flag;

			$admission = Admission::find($id);
			$patient = $admission->encounter->patient;
			$encounter= Encounter::find($admission->encounter_id);

			$admission_beds = DB::table('beds as a')
					->select(['block_room', 'status_code', 'b.admission_id','a.bed_code','bed_name','room_name','patient_name','a.ward_code', 'ward_name','class_name', 'a.class_code','c.patient_id'])
					->leftJoin('admissions as b', 'b.bed_code', '=', 'a.bed_code')
					->leftJoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftJoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
					->leftJoin('discharges as e', 'e.encounter_id', '=', 'c.encounter_id')
					->leftJoin('ward_classes as f', 'f.class_code', '=', 'a.class_code')
					->leftJoin('wards as g', 'g.ward_code', '=', 'a.ward_code')
					->leftJoin('ward_rooms as h', 'h.room_code', '=', 'a.room_code')
					->where('a.class_code','like','%'.$request->ward_class.'%')
					->where('a.ward_code','like','%'.$request->ward_code.'%')
					->whereNull('discharge_id')
					->orderBy('room_name')
					->orderBy('bed_name')
					->orderBy('ward_name')
					->paginate($this->paginateValue);

			if (!empty($request->book_id)) {
					$book_id = $request->book_id;
					$book = BedBooking::find($book_id);
			}

			return view('admission_beds.index', [
					'admission_beds'=>$admission_beds,
					'wards2' => Ward::where('ward_code', '<>', 'mortuary')->where('ward_code', '<>', 'observation')->orderBy('ward_name')->get(),
					'ward_code'=>$request->ward_code,
					'ward_class'=>$request->ward_class,
					'admission' => $admission,
					'patient' => $patient,
					'encounter' => $encounter,
					'flag' => $flag,
					'ward_classes' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'book' => $book,
					'book_id' => $book_id,
					'current_bed'=>$this->getCurrentBed($admission->encounter_id),
					'current_ward'=>$request->cookie('ward'),
					]);
	}

	public function confirm(Request $request,$admission_id, $bed_code)
	{
			$book_id = NULL;
			$admission = Admission::find($admission_id);
			$bed = Bed::find($bed_code);

			if (!empty($request->book_id)) {
				$book_id = $request->book_id;
			}
			return view('admission_beds.confirm', [
					'admission' => $admission, 
					'bed_code' => $bed_code,
					'current_bed'=>$this->getCurrentBed($admission->encounter_id),
					'patient'=>$admission->encounter->patient,
					'bed'=>$bed,
					'book_id' => $book_id,
			]);
	}

	public function move(Request $request,$admission_id, $bed_code)
	{
			$admission = Admission::find($admission_id);
			$new_bed = Bed::find($bed_code);
			
			/** Set current bed to housekeeping **/
			$current_bed = Bed::find($admission->bed_code);
			$current_bed->status_code = "02";
			$current_bed->save();

			/** Release beds if room blocked **/
			if ($admission->block_room==1) {
				$block_beds = Bed::where('room_code',$current_bed->room_code)
								->where('bed_code', '<>', $current_bed->bed_code)
								->get();

				foreach ($block_beds as $blocked_bed) {
						DB::table('beds')
								->where('bed_code', $blocked_bed->bed_code)
								->update(['status_code'=>'02']);
				}

			}	

			if ($current_bed->bed_transit==1) {
				$current_bed_charge = BedCharge::where('encounter_id',$admission->encounter_id)
						->where('bed_code', $current_bed->bed_code)
						->whereNull('bed_stop')
						->first();
				$current_bed_charge->bed_stop = date('d/m/Y');
				$current_bed_charge->save();
			}
			/**/

			$bed_movement = new BedMovement();
			$bed_movement->admission_id = $admission_id;
			$bed_movement->encounter_id = $admission->encounter_id;
			$bed_movement->move_from = $admission->bed_code;
			$bed_movement->move_to = $bed_code;
			$bed_movement->move_date = date('d/m/Y');

			if ($bed_movement->move_from == $bed_movement->move_to) { 
				$transaction = "admission";
			} else {
				if ($bed_movement->bedFrom->ward_code == $bed_movement->bedTo->ward_code) {
						if ($bed_movement->bedFrom->class_code == $bed_movement->bedTo->class_code) {
							$transaction = "swap";
						} else {
							$transaction = "change";
						}
				} else {
					$transaction = "transfer";
				}	
			}
			$bed_movement->transaction_code = $transaction;
			$bed_movement->save();
			
			/** Update admission table **/
			if ($new_bed->bed_transit == 0) {
					$admission->anchor_bed = $bed_code;
			}
			$admission->bed_code = $bed_code;
			$admission->save();

			/** Set new bed to occupiied **/
			$new_bed->status_code = '03';
			$new_bed->save();

			if ($new_bed->bed_transit==0) {
				$open_bed = BedCharge::where('encounter_id',$admission->encounter_id)
							->whereNull('bed_stop')
							->first();
				if ($open_bed->bed_code != $new_bed->bed_code) {
						$open_bed->bed_stop = date('d/m/Y');
						$open_bed->save();
				}

			}

			/** Is returning bed ? **/
			$return_bed = BedCharge::where('encounter_id',$admission->encounter_id)
				->whereNull('bed_stop')
				->first();

			$add_bed=false;

			if (!$return_bed) $add_bed=true;
			if ($return_bed) {
				if ($new_bed->bed_code != $return_bed->bed_code) $add_bed=true;
			}

			if ($add_bed) {
						$new_bed_charge = new BedCharge();
						$new_bed_charge->encounter_id = $admission->encounter_id;
						$new_bed_charge->bed_code = $new_bed->bed_code;
						$new_bed_charge->bed_start = date('d/m/Y');
						$new_bed_charge->block_room = $admission->block_room;
						$new_bed_charge->save();

						if ($admission->block_room==1) {
								$other_beds = Bed::where('room_code',$new_bed->room_code)
												->where('bed_code', '<>', $new_bed->bed_code)
												->get();

								foreach ($other_beds as $other_bed) {
										DB::table('beds')
												->where('bed_code', $other_bed->bed_code)
												->update(['status_code'=>'05']);
								}

						}	
			}

			if (!empty($request->book_id)) {
				BedBooking::find($request->book_id)->delete();	
			}

			$moves = 0;
			$moves = BedMovement::where('encounter_id', '=',$admission->encounter_id)->count();

			return view('admissions.complete', [
					'encounter'=>$admission->encounter,
					'admission'=>$admission,
					'patient'=>$admission->encounter->patient,
					'moves'=>$moves,
			]);

	}
	
}
