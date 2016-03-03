<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\QueueLocation;
use Log;
use DB;
use Session;
use App\Department;
use App\EncounterType as Encounter;


class QueueLocationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$queue_locations = DB::table('queue_locations')
					->orderBy('location_name')
					->paginate($this->paginateValue);
			return view('queue_locations.index', [
					'queue_locations'=>$queue_locations
			]);
	}

	public function create()
	{
			$queue_location = new QueueLocation();
			return view('queue_locations.create', [
					'queue_location' => $queue_location,
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					'encounter' => Encounter::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$queue_location = new QueueLocation();
			$valid = $queue_location->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$queue_location = new QueueLocation($request->all());
					$queue_location->location_code = $request->location_code;
					$queue_location->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/queue_locations/id/'.$queue_location->location_code);
			} else {
					return redirect('/queue_locations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$queue_location = QueueLocation::findOrFail($id);
			return view('queue_locations.edit', [
					'queue_location'=>$queue_location,
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					'encounter' => Encounter::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$queue_location = QueueLocation::findOrFail($id);
			$queue_location->fill($request->input());

			$queue_location->location_is_pool = $request->location_is_pool ?: 0;

			$valid = $queue_location->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$queue_location->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/queue_locations/id/'.$id);
			} else {
					return view('queue_locations.edit', [
							'queue_location'=>$queue_location,
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					'encounter' => Encounter::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$queue_location = QueueLocation::findOrFail($id);
		return view('queue_locations.destroy', [
			'queue_location'=>$queue_location
			]);

	}
	public function destroy($id)
	{	
			QueueLocation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/queue_locations');
	}
	
	public function search(Request $request)
	{
			$queue_locations = DB::table('queue_locations')
					->where('location_name','like','%'.$request->search.'%')
					->orWhere('location_code', 'like','%'.$request->search.'%')
					->orderBy('location_name')
					->paginate($this->paginateValue);

			return view('queue_locations.index', [
					'queue_locations'=>$queue_locations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$queue_locations = DB::table('queue_locations')
					->where('location_code','=',$id)
					->paginate($this->paginateValue);

			return view('queue_locations.index', [
					'queue_locations'=>$queue_locations
			]);
	}
}
