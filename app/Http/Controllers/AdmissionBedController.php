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

class AdmissionBedController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$admission = NULL;
			$patient = NULL;
			$flag=$request->flag;
			$encounter = NULL;
			$ward_class = $request->ward_class;
			$book_id = NULL;
			if (!empty($request->admission_id)) {
					$admission = Admission::find($request->admission_id);
					$patient = $admission->encounter->patient;
					$encounter= Encounter::find($admission->encounter_id);


					if ($encounter->encounter_code =='emergency') $ward_code = 'observation';
			}

			if (!empty($request->book_id)) {
					$book_id = $request->book_id;
					$book = BedBooking::find($book_id);
					$ward_class=$book->class_code;

			}

			$admission_beds = DB::table('beds as a')
					->select(['b.admission_id','a.bed_code','bed_name','patient_name','ward_code', 'a.class_code', 'class_name','c.patient_id'])
					->leftJoin('admissions as b', 'b.bed_code', '=', 'a.bed_code')
					->leftJoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftJoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
					->leftJoin('discharges as e', 'e.encounter_id', '=', 'c.encounter_id')
					->leftJoin('ward_classes as f', 'f.class_code', '=', 'a.class_code')
					->whereNull('discharge_id')
					->where('a.class_code','=',$ward_class)
					->orderBy('bed_name')
					->paginate($this->paginateValue);

			return view('admission_beds.index', [
					'admission_beds'=>$admission_beds,
					'ward' => Ward::where('encounter_code','=', $encounter->encounter_code)->orderBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward_class' => $ward_class,
					'admission' => $admission,
					'patient' => $patient,
					'flag' => $flag,
					'encounter' => $encounter,
					'book_id' => $book_id,
					'ward_classes' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
			]);
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
			$valid = $admission_bed->validate($request->all(), $request->_method);

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

			$admission_bed->bed_virtual = $request->bed_virtual ?: 0;

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
			$flag=$request->flag;
			if (!empty($request->admission_id)) {
					$admission = Admission::find($request->admission_id);
					$patient = $admission->encounter->patient;
					$encounter= Encounter::find($admission->encounter_id);
			}
			$admission_beds = DB::table('beds as a')
					->select(['b.admission_id','a.bed_code','bed_name','patient_name','ward_code','class_name', 'a.class_code','c.patient_id'])
					->leftJoin('admissions as b', 'b.bed_code', '=', 'a.bed_code')
					->leftJoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftJoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
					->leftJoin('discharges as e', 'e.encounter_id', '=', 'c.encounter_id')
					->leftJoin('ward_classes as f', 'f.class_code', '=', 'a.class_code')
					->where('a.class_code','like','%'.$request->ward_class.'%')
					->whereNull('discharge_id')
					->orderBy('patient_name')
					->orderBy('bed_name')
					->paginate($this->paginateValue);

			return view('admission_beds.index', [
					'admission_beds'=>$admission_beds,
					'ward' => Ward::where('encounter_code','=', $encounter->encounter_code)->orderBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward_class'=>$request->ward_class,
					'admission' => $admission,
					'patient' => $patient,
					'encounter' => $encounter,
					'flag' => $flag,
					'ward_classes' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					]);
	}

	public function searchById($id)
	{
			$admission_beds = DB::table('beds')
					->where('bed_code','=',$id)
					->paginate($this->paginateValue);

			return view('admission_beds.index', [
					'admission_beds'=>$admission_beds
			]);
	}

	public function move(Request $request,$admission_id, $bed_code)
	{
			$admission = Admission::find($admission_id);
			
			$bed_movement = new BedMovement();
			$bed_movement->admission_id = $admission_id;
			$bed_movement->encounter_id = $admission->encounter_id;
			$bed_movement->move_from = $admission->bed_code;
			$bed_movement->move_to = $bed_code;
			$bed_movement->move_date = date('d/m/Y');
			$bed_movement->save();

			$admission->bed_code = $bed_code;
			$admission->save();

			if (!empty($request->book_id)) {
				BedBooking::find($request->book_id)->delete();	
			}

			$moves = 0;
			$moves = BedMovement::where('encounter_id', '=',$admission->encounter_id)->count();

			return view('admissions.complete', [
					'admission'=>$admission,
					'patient'=>$admission->encounter->patient,
					'moves'=>$moves,
			]);

	}
	
}
