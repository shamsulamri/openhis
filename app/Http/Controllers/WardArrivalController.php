<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\WardArrival;
use Log;
use DB;
use Session;
use App\Encounter;
use App\Bed;
use App\Admission;
use App\BedMovement;
use App\BedCharge;

class WardArrivalController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$ward_arrivals = DB::table('ward_arrivals')
					->orderBy('arrival_description')
					->paginate($this->paginateValue);
			return view('ward_arrivals.index', [
					'ward_arrivals'=>$ward_arrivals
			]);
	}

	public function create(Request $request, $encounter_id)
	{
			$encounter = Encounter::find($encounter_id);
			$ward_arrival = new WardArrival();
			$ward_arrival->encounter_id = $encounter_id;
			$ward_code = $request->cookie('ward');

			$bed = Bed::find($encounter->admission->bed_code);
			$bed_name = $bed->bed_name;
			if (isset($bed->room->room_name)) {
					$bed_name += ' ('.$bed->room->room_name.')';
			}


			$beds = Bed::select(DB::raw("concat(bed_name, ' (', room_name, ')') as bed_name, bed_code"))
						->leftJoin('ward_rooms as b', 'b.room_code', '=', 'beds.room_code')
						->where('status_code','01')
						->where('ward_code', $ward_code)
						->where('class_code', $bed->class_code)
						->orderBy('bed_name')
						->lists('bed_name', 'bed_code')
						->prepend($bed_name,$bed->bed_code);

			return view('ward_arrivals.create', [
					'ward_arrival' => $ward_arrival,
					'patient' => $encounter->patient,	
					'encounter' => $encounter,
					'beds' => $beds,
					]);
	}

	public function store(Request $request) 
	{
			$ward_arrival = new WardArrival();
			$valid = $ward_arrival->validate($request->all(), $request->_method);

			$encounter = Encounter::find($request->encounter_id);
			$bed_assigned = $encounter->admission->bed;
			$admission = Admission::find($encounter->admission->admission_id);
			$admission->bed_code = $request->bed_code;
			$admission->save();

			if ($bed_assigned->bed_code != $request->bed_code) {
							$bed_assigned->status_code = '01';
							$bed_assigned->save();

							$bed = Bed::find($request->bed_code);
							$bed->status_code = '03';
							$bed->save();
			}

			$bed_movement = BedMovement::where('admission_id', $encounter->admission->admission_id)->first();
			$bed_movement->move_from = $request->bed_code;
			$bed_movement->move_to = $request->bed_code;
			$bed_movement->save();

			$bed_charge = BedCharge::where('bed_code',$bed_assigned->bed_code)
							->where('encounter_id', $encounter->encounter_id)
							->first();
			$bed_charge->bed_code = $request->bed_code;
			$bed_charge->save();
							

			if ($valid->passes()) {
					$ward_arrival = new WardArrival($request->all());
					$ward_arrival->arrival_id = $request->arrival_id;
					$ward_arrival->encounter_id = $request->encounter_id;
					$ward_arrival->save();
					Session::flash('message', 'Patient arrival confirmed.');
					return redirect('/admissions');
			} else {
					return redirect('/ward_arrivals/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$ward_arrival = WardArrival::findOrFail($id);
			return view('ward_arrivals.edit', [
					'ward_arrival'=>$ward_arrival,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$ward_arrival = WardArrival::findOrFail($id);
			$ward_arrival->fill($request->input());


			$valid = $ward_arrival->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$ward_arrival->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/ward_arrivals/id/'.$id);
			} else {
					return view('ward_arrivals.edit', [
							'ward_arrival'=>$ward_arrival,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$ward_arrival = WardArrival::findOrFail($id);
		return view('ward_arrivals.destroy', [
			'ward_arrival'=>$ward_arrival
			]);

	}
	public function destroy($id)
	{	
			WardArrival::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/ward_arrivals');
	}
	
	public function search(Request $request)
	{
			$ward_arrivals = DB::table('ward_arrivals')
					->where('arrival_description','like','%'.$request->search.'%')
					->orWhere('arrival_id', 'like','%'.$request->search.'%')
					->orderBy('arrival_description')
					->paginate($this->paginateValue);

			return view('ward_arrivals.index', [
					'ward_arrivals'=>$ward_arrivals,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$ward_arrivals = DB::table('ward_arrivals')
					->where('arrival_id','=',$id)
					->paginate($this->paginateValue);

			return view('ward_arrivals.index', [
					'ward_arrivals'=>$ward_arrivals
			]);
	}
}
