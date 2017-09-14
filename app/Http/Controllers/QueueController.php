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

class QueueController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$selectedLocation = $request->cookie('queue_location');

			$queues = DB::table('queues as a')
					->select('queue_id', 'patient_mrn', 'patient_name', 'location_name', 'a.location_code', 'a.created_at', 'a.encounter_id')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftjoin('queue_locations as d', 'd.location_code','=', 'a.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->whereNull('discharge_id')
					->whereNull('a.deleted_at')
					->orderBy('a.created_at')
					->paginate($this->paginateValue);

			$location = Location::find($selectedLocation);
			
			return view('queues.index', [
					'queues'=>$queues,
					'locations' => Location::whereNotNull('encounter_code')->orderBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'location' => $location,
					'selectedLocation' => "",
					'dojo'=>new DojoUtility(),
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
			$encounter = Encounter::findOrFail($queue->encounter_id);
			$locations = Location::where('encounter_code',$encounter->encounter_code)->orderBy('location_name')->get();
			$location = Location::where('encounter_code',$encounter->encounter_code)
					->where('location_code','<>', $queue->location_code)
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
			$selectedLocation = $request->locations;

			$queues = DB::table('queues as a')
					->select('queue_id', 'patient_mrn', 'patient_name', 'location_name', 'a.created_at', 'a.encounter_id')
					->join('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->join('patients as c', 'c.patient_id','=', 'b.patient_id')
					->join('queue_locations as d', 'd.location_code','=', 'a.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->where('a.location_code','like','%'.$request->locations.'%')
					->where('patient_name', 'like','%'.$request->search.'%')
					->whereNull('discharge_id')
					->orderBy('a.created_at')
					->paginate($this->paginateValue);

			$location = Location::find($request->cookie('queue_location'));
			
			return view('queues.index', [
					'queues'=>$queues,
					'locations' => Location::whereNotNull('encounter_code')->orderBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'location' => $location,
					'search' => $request->search,
					'selectedLocation' => $selectedLocation,
					'dojo'=>new DojoUtility(),
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
					'date_start'=>$date_start,
					'date_end'=>$date_end,
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
