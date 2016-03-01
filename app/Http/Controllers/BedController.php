<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Bed;
use Log;
use DB;
use Session;
use App\WardClass;
use App\Ward;
use App\Room;
use App\BedStatus;
use App\Gender;
use App\Department;
use App\EncounterType;


class BedController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$beds = DB::table('beds')
					->orderBy('bed_name')
					->paginate($this->paginateValue);
			return view('beds.index', [
					'beds'=>$beds
			]);
	}

	public function create()
	{
			$bed = new Bed();
			return view('beds.create', [
					'bed' => $bed,
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'room' => Room::all()->sortBy('room_name')->lists('room_name', 'room_code')->prepend('',''),
					'status' => BedStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$bed = new Bed();
			$valid = $bed->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bed = new Bed($request->all());
					$bed->bed_code = $request->bed_code;
					$bed->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/beds/id/'.$bed->bed_code);
			} else {
					return redirect('/beds/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bed = Bed::findOrFail($id);
			return view('beds.edit', [
					'bed'=>$bed,
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'room' => Room::all()->sortBy('room_name')->lists('room_name', 'room_code')->prepend('',''),
					'status' => BedStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$bed = Bed::findOrFail($id);
			$bed->fill($request->input());


			$valid = $bed->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bed->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/beds/id/'.$id);
			} else {
					return view('beds.edit', [
							'bed'=>$bed,
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'room' => Room::all()->sortBy('room_name')->lists('room_name', 'room_code')->prepend('',''),
					'status' => BedStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bed = Bed::findOrFail($id);
		return view('beds.destroy', [
			'bed'=>$bed
			]);

	}
	public function destroy($id)
	{	
			Bed::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/beds');
	}
	
	public function search(Request $request)
	{
			$beds = DB::table('beds')
					->where('bed_name','like','%'.$request->search.'%')
					->orWhere('bed_code', 'like','%'.$request->search.'%')
					->orderBy('bed_name')
					->paginate($this->paginateValue);

			return view('beds.index', [
					'beds'=>$beds,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$beds = DB::table('beds')
					->where('bed_code','=',$id)
					->paginate($this->paginateValue);

			return view('beds.index', [
					'beds'=>$beds
			]);
	}
}
