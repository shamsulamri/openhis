<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Queue;
use Log;
use DB;
use Session;
use App\QueueLocation as Location;
use App\Encounter;
use Auth;
use App\DojoUtility;
use App\User;
use App\EncounterType;
use App\QueueLocation;

class QueueController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			/*
			if (empty($request->cookie('queue_location'))) {
					//return "Location not set";
					return redirect('queue_locations');
			}

			if (!empty(Auth::user()->authorization->location_code)) {
				$location_code = Auth::user()->authorization->location_code;
			} else {
				$location_code = $request->cookie('queue_location');
			}
			 */

			$location = null;
			$location_code = null;
			$encounter_code = null;

			if (!empty($request->cookie('queue_location'))) {
					$location_code = $request->cookie('queue_location');
			}

			if (!empty($request->queue_id)) {
				$queue = Queue::find($request->queue_id);
				$location_code = $queue->location_code;
			}

			if (!empty($location_code)) {
					$location = QueueLocation::find($location_code);
					$encounter_code = $location->encounter_code;
			}



			/**
			if (empty($request->cookie('queue_location')) & empty(Auth::user()->location_code)) {
					Session::flash('message', 'Location not set. Please select your location or room.');
					return redirect('/queue_locations');
			}
			**/

			/**
			$queues = DB::table('queues as a')
					->select('queue_id', 'patient_mrn', 'patient_name', 'location_name', 'a.location_code', 'a.created_at', 'a.encounter_id')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftjoin('queue_locations as d', 'd.location_code','=', 'a.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->whereNull('discharge_id')
					->whereNull('a.deleted_at')
					->orderBy('a.created_at');
			**/

			$queues = Queue::select('queue_id', 'patient_mrn', 'patient_name', 'location_name', 'queues.location_code', 'queues.created_at', 'queues.encounter_id')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'queues.encounter_id')
					->leftjoin('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftjoin('queue_locations as d', 'd.location_code','=', 'queues.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->whereNull('discharge_id')
					->whereNull('queues.deleted_at')
					->whereNull('b.deleted_at')
					->orderBy('queues.created_at');

			if (!empty($location_code)) {
					$queues = $queues->where('queues.location_code','=',$location_code);
			}

			$queues = $queues->paginate($this->paginateValue);

			$locations = Location::whereNotNull('encounter_code');

			if (!empty($location)) {
					$locations = $locations->where('encounter_code', $location->encounter_code);
					if (!empty($locations)) {
						$encounter_code = $location->encounter_code;
					}
			}

			$locations = $locations->orderBy('location_name')
							->lists('location_name', 'location_code')->prepend('','');
			
			$encounters = EncounterType::all()->lists('encounter_name', 'encounter_code')
					->sortBy('encounter_name')
					->prepend('','');

			return view('queues.index', [
					'queues'=>$queues,
					'locations' => $locations,
					'encounters' => $encounters,
					'location' => $location,
					'selectedLocation' => $location_code,
					'dojo'=>new DojoUtility(),
					'encounter_code'=>$encounter_code,
			]);
	}

	public function create(Request $request)
	{
			$encounter = new Encounter();
			$queue = new Queue();
			$location = new Location();
			
			if (empty($request->encounter_id)==false) {
					$queue->encounter_id = $request->encounter_id;
					$encounter = Encounter::findOrFail($queue->encounter_id);
					$location = Location::where('encounter_code',$encounter->encounter_code)->orderBy('location_name')->lists('location_name','location_code')->prepend('','');
					$locations= Location::where('encounter_code',$encounter->encounter_code)->orderBy('location_name')->get();
			}
			
			return view('queues.create', [
					'queue' => $queue,
					'patient' => $encounter->patient,
					'location' => $location,
					'locations' => $locations,
					'encounter' => $encounter,
			]);
	}

	public function store(Request $request) 
	{
			$queue = new Queue();
			$valid = $queue->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$queue = new Queue($request->all());
					$queue->queue_id = $request->queue_id;
					$queue->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/queues');
			} else {
					return redirect('/queues/create?encounter_id='.$request->encounter_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$queue = Queue::findOrFail($id);
			$encounter = Encounter::find($queue->encounter_id);
			$locations = Location::where('encounter_code',$encounter->encounter_code)->orderBy('location_name')->get();
			$location = Location::where('encounter_code',$encounter->encounter_code)
					->orderBy('location_name')
					->lists('location_name', 'location_code');

			return view('queues.edit', [
					'queue'=>$queue,
					'patient'=> $encounter->patient,
					'location'=>$location,
					'locations' => $locations,
					'encounter' => $encounter,
					]);
	}

	public function update(Request $request, $id) 
	{

			$queue = Queue::findOrFail($id);
			$queue->fill($request->input());
			$encounter = $queue->encounter;

			$encounter->encounter_description = $request->encounter_description;
			$encounter->save();

			$valid = $queue->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$queue->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/queues');
			} else {
					return view('queues.edit', [
							'queue'=>$queue,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$queue = Queue::findOrFail($id);
		return view('queues.destroy', [
			'queue'=>$queue
			]);

	}
	public function destroy($id)
	{	
			Queue::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/queues');
	}
	
	public function search(Request $request)
	{
			/*
			$queues = DB::table('queues as a')
					->select('queue_id', 'patient_mrn', 'patient_name', 'location_name', 'a.created_at', 'a.encounter_id', 'a.location_code')
					->join('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->join('patients as c', 'c.patient_id','=', 'b.patient_id')
					->join('queue_locations as d', 'd.location_code','=', 'a.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->where('a.location_code','like','%'.$request->locations.'%')
					->where('patient_name', 'like','%'.$request->search.'%')
					->whereNull('discharge_id')
					->orderBy('a.created_at');
			 */

			$queues = Queue::select('queue_id', 'patient_mrn', 'patient_name', 'location_name', 'queues.location_code', 'queues.created_at', 'queues.encounter_id')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'queues.encounter_id')
					->leftjoin('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftjoin('queue_locations as d', 'd.location_code','=', 'queues.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->whereNull('discharge_id')
					->whereNull('queues.deleted_at')
					->orderBy('queues.created_at');

			$queues = Queue::select('queue_id', 'patient_mrn', 'patient_name', 'location_name', 'queues.location_code', 'queues.created_at', 'queues.encounter_id')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'queues.encounter_id')
					->leftjoin('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftjoin('queue_locations as d', 'd.location_code','=', 'queues.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->whereNull('discharge_id')
					->whereNull('queues.deleted_at')
					->where('queues.location_code','like','%'.$request->locations.'%')
					->where('patient_name', 'like','%'.$request->search.'%')
					->whereNull('b.deleted_at')
					->orderBy('queues.created_at', 'desc');

			if ($request->encounter_code) {
					$queues = $queues->where('b.encounter_code', $request->encounter_code);
			}

			$queues = $queues->paginate($this->paginateValue);

			//$location = Location::find($request->cookie('queue_location'));
			if (!empty($request->location)) {
				$selectedLocation = $request->cookie('queue_location');
			} else {
				$selectedLocation = $request->locations;
			}
			$location = Location::find($selectedLocation);
			$locations = Location::whereNotNull('encounter_code')
							->where('encounter_code', $request->encounter_code)
							->orderBy('location_name')
							->lists('location_name', 'location_code')->prepend('','');

			if (empty($request->encounter_code)) {
					$locations = Location::whereNotNull('encounter_code')
							->orderBy('location_name')
							->lists('location_name', 'location_code')->prepend('','');
			}

			$encounters = EncounterType::all()->lists('encounter_name', 'encounter_code')
					->sortBy('encounter_name')
					->prepend('','');

			return view('queues.index', [
					'queues'=>$queues,
					'locations' => $locations,
					'location' => $location,
					'search' => $request->search,
					'selectedLocation' => $selectedLocation,
					'dojo'=>new DojoUtility(),
					'encounters' => $encounters,
					'encounter_code'=>$request->encounter_code,
				]);
	}

	public function searchById(Request $request, $id)
	{
			$queues = DB::table('queues')
					->where('queue_id','=',$id)
					->paginate($this->paginateValue);

			$queues = DB::table('queues as a')
					->select('queue_id', 'patient_name', 'location_name', 'a.created_at', 'a.encounter_id')
					->join('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->join('patients as c', 'c.patient_id','=', 'b.patient_id')
					->join('queue_locations as d', 'd.location_code','=', 'a.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->where('a.location_code','=',$request->cookie('queue_location'))
					->whereNull('discharge_id')
					->where('queue_id','=',$id)
					->orderBy('a.created_at')
					->paginate($this->paginateValue);

			$location = Location::find($request->cookie('queue_location'));

			return view('queues.index', [
					'queues'=>$queues,
					'location' => $location,
			]);
	}

	public function enquiry(Request $request)
	{
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$selectedLocation = $request->locations;

			$queues = Queue::select('queue_id', 'patient_mrn', 'patient_name', 'location_name', 'queues.created_at', 'queues.encounter_id','name')
					->join('encounters as b', 'b.encounter_id','=', 'queues.encounter_id')
					->join('patients as c', 'c.patient_id','=', 'b.patient_id')
					->join('queue_locations as d', 'd.location_code','=', 'queues.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->leftJoin('users as f', 'f.id', '=', 'd.user_id')
					->where('queues.location_code','like','%'.$request->locations.'%')
					->where('patient_name', 'like','%'.$request->search.'%')
					->orderBy('queues.queue_id', 'desc');

			if (!empty($date_start) && empty($request->date_end)) {
				$queues = $queues->where('queues.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$queues = $queues->where('queues.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$queues = $queues->whereBetween('queues.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if (!empty($request->user_id)) {
					$queues = $queues->where('d.user_id','=',$request->user_id);
			}

			if ($request->export_report) {
				DojoUtility::export_report($queues->get());
			}

			$queues = $queues->paginate($this->paginateValue);

			$location = Location::find($request->cookie('queue_location'));
			
			return view('queues.enquiry', [
					'date_start'=>$request->date_start,
					'date_end'=>$request->date_end,
					'queues'=>$queues,
					'locations' => Location::whereNotNull('encounter_code')->orderBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'location' => $location,
					'search' => $request->search,
					'selectedLocation' => $selectedLocation,
					'dojo'=>new DojoUtility(),
					'consultants' => $this->getConsultants(),
					'user_id' => $request->user_id,
				]);
	}

	public function getConsultants()
	{
			$consultants = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('consultant',1)
							->orderBy('name')
							->lists('name','id')
							->prepend('','');
			return $consultants;
	}
}
