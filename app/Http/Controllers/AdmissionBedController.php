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
			if (!empty($request->admission_id)) {
					$admission = Admission::find($request->admission_id);
					$patient = $admission->encounter->patient;
			}
			$admission_beds = DB::table('beds as a')
					->select(['a.bed_code','bed_name','patient_name'])
					->leftJoin('admissions as b', 'b.bed_code', '=', 'a.bed_code')
					->leftJoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftJoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
					->leftJoin('discharges as e', 'e.encounter_id', '=', 'c.encounter_id')
					->whereNull('discharge_id')
					->orderBy('bed_name')
					->paginate($this->paginateValue);
			return view('admission_beds.index', [
					'admission_beds'=>$admission_beds,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward_code' => '',
					'admission' => $admission,
					'patient' => $patient,
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
			if (!empty($request->admission_id)) {
					$admission = Admission::find($request->admission_id);
					$patient = $admission->encounter->patient;
			}
			$admission_beds = DB::table('beds as a')
					->select(['a.bed_code','bed_name','patient_name'])
					->leftJoin('admissions as b', 'b.bed_code', '=', 'a.bed_code')
					->leftJoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftJoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
					->leftJoin('discharges as e', 'e.encounter_id', '=', 'c.encounter_id')
					->where('ward_code','like','%'.$request->wards.'%')
					->whereNull('discharge_id')
					->orderBy('bed_name')
					->paginate($this->paginateValue);

			return view('admission_beds.index', [
					'admission_beds'=>$admission_beds,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward_code'=>$request->wards,
					'admission' => $admission,
					'patient' => $patient,
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

	public function admit($admission_id, $bed_code)
	{
			$admission = Admission::find($admission_id);
			$admission->bed_code = $bed_code;
			$admission->save();
			
			return redirect('/admissions');
	}
	
}
