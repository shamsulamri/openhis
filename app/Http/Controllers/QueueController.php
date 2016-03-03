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


class QueueController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$queues = DB::table('queues')
					->join('encounters', 'encounters.encounter_id','=', 'queues.encounter_id')
					->join('patients', 'patients.patient_id','=', 'encounters.patient_id')
					->join('queue_locations', 'queue_locations.location_code','=', 'queues.location_code')
					->leftJoin('consultations', 'consultations.encounter_id','=', 'queues.encounter_id')
					->select('queue_id', 'patient_name', 'location_name', 'queues.created_at', 'queues.encounter_id', 'consultation_id')
					->orderBy('queues.created_at')
					->paginate($this->paginateValue);
			return view('queues.index', [
					'queues'=>$queues
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
					$location = Location::where('encounter_code',$encounter->encounter_code)->lists('location_name','location_code')->prepend('','');
			}
			
			return view('queues.create', [
					'queue' => $queue,
					'location' => $location
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
					return redirect('/queues/id/'.$queue->queue_id);
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

			return view('queues.edit', [
					'queue'=>$queue,
					'location' => Location::where('encounter_code',$encounter->encounter_code)->lists('location_name','location_code')->prepend('',''),
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
					return redirect('/queues/id/'.$id);
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
			$queues = DB::table('queues')
					->join('encounters', 'encounters.encounter_id','=', 'queues.encounter_id')
					->join('patients', 'patients.patient_id','=', 'encounters.patient_id')
					->join('queue_locations', 'queue_locations.location_code','=', 'queues.location_code')
					->where('location_name','like','%'.$request->search.'%')
					->orWhere('patient_name', 'like','%'.$request->search.'%')
					->orderBy('queues.created_at')
					->paginate($this->paginateValue);

			return view('queues.index', [
					'queues'=>$queues,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$queues = DB::table('queues')
					->where('queue_id','=',$id)
					->paginate($this->paginateValue);

			return view('queues.index', [
					'queues'=>$queues
			]);
	}
}
