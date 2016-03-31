<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderQueue;
use Log;
use DB;
use Session;
use App\QueueLocation;

class OrderQueueController extends Controller
{
	public $paginateValue=10;

	public $fields = ['a.order_id',
					'patient_name', 
					'patient_mrn',	
					'location_name',	
					'c.encounter_id',	
					'g.location_code',
					'cancel_id',
					'j.created_at',
					'bed_name',
					];
	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$location = $request->cookie('queue_location');
			$order_queues = DB::table('orders as a')
					->distinct()
					->select($this->fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->join('products as e','e.product_code','=','a.product_code')
					->leftjoin('order_cancellations as f', 'f.order_id', '=', 'a.order_id')
					->leftjoin('product_categories as g', 'g.category_code', '=', 'e.category_code')
					->leftjoin('queues as h', 'h.encounter_id', '=', 'c.encounter_id')
					->leftjoin('queue_locations as i', 'i.location_code', '=', 'h.location_code')
					->leftjoin('order_posts as j', 'j.post_id', '=', 'a.post_id')
					->leftjoin('users as k','k.id','=', 'b.user_id')
					->leftjoin('admissions as l', 'l.encounter_id', '=', 'c.encounter_id')
					->leftjoin('beds as m', 'm.bed_code', '=', 'l.bed_code')
					->where('a.post_id','>',0)
					->whereNull('cancel_id')
					->where('g.location_code','=',$location)
					->where('order_completed', '=', 0)
					->orderBy('a.post_id')
					->orderBy('a.created_at')
					->orderBy('order_is_discharge','desc')
					->orderBy('a.created_at', 'desc')
					->groupBy('c.encounter_id')
					->paginate($this->paginateValue);

			$fields = ['a.order_id',
					'patient_name', 
					'patient_mrn',	
					'encounter_name', 
					'c.encounter_id',	
					'g.location_code',
					'cancel_id',
					'a.created_at',
					];
			$order_queues = DB::table('orders as a')
					->select($fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'a.order_id')
					->leftjoin('queue_locations as g', 'g.location_code', '=', 'a.location_code')
					->leftjoin('ref_encounter_types as h', 'h.encounter_code', '=', 'c.encounter_code')
					->where('a.location_code','=',$location)
					->whereNull('cancel_id')
					->where('order_completed', '=', 0)
					->groupBy('a.encounter_id')
					->paginate($this->paginateValue);
			return view('order_queues.index', [
					'order_queues'=>$order_queues,
					'search'=>$request->search,
					'locations' => QueueLocation::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'location'=>$location,
					]);
	}

	public function create()
	{
			$order_queue = new OrderQueue();
			return view('order_queues.create', [
					'order_queue' => $order_queue,
				
					]);
	}

	public function store(Request $request) 
	{
			$order_queue = new OrderQueue();
			$valid = $order_queue->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_queue = new OrderQueue($request->all());
					$order_queue->post_id = $request->post_id;
					$order_queue->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/order_queues/id/'.$order_queue->post_id);
			} else {
					return redirect('/order_queues/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_queue = OrderQueue::findOrFail($id);
			return view('order_queues.edit', [
					'order_queue'=>$order_queue,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_queue = OrderQueue::findOrFail($id);
			$order_queue->fill($request->input());


			$valid = $order_queue->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_queue->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_queues/id/'.$id);
			} else {
					return view('order_queues.edit', [
							'order_queue'=>$order_queue,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_queue = OrderQueue::findOrFail($id);
		return view('order_queues.destroy', [
			'order_queue'=>$order_queue
			]);

	}
	public function destroy($id)
	{	
			OrderQueue::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_queues');
	}
	
	public function search(Request $request)
	{
			$location = $request->locations;
			$order_queues = DB::table('orders as a')
					->distinct()
					->select($this->fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->join('products as e','e.product_code','=','a.product_code')
					->leftjoin('order_cancellations as f', 'f.order_id', '=', 'a.order_id')
					->leftjoin('product_categories as g', 'g.category_code', '=', 'e.category_code')
					->leftjoin('queues as h', 'h.encounter_id', '=', 'c.encounter_id')
					->leftjoin('queue_locations as i', 'i.location_code', '=', 'h.location_code')
					->leftjoin('order_posts as j', 'j.post_id', '=', 'a.post_id')
					->leftjoin('admissions as l', 'l.encounter_id', '=', 'c.encounter_id')
					->leftjoin('beds as m', 'm.bed_code', '=', 'l.bed_code')
					->where('a.post_id','>',0)
					->where('g.location_code','=',$location)
					->where('order_completed', '=', 0)
					->orderBy('a.post_id')
					->orderBy('a.created_at')
					->orderBy('order_is_discharge','desc')
					->orderBy('a.created_at', 'desc')
					->groupBy('c.encounter_id')
					->paginate($this->paginateValue);

			$fields = ['a.order_id',
					'patient_name', 
					'patient_mrn',	
					'encounter_name', 
					'c.encounter_id',	
					'g.location_code',
					'cancel_id',
					'a.created_at',
					];
			$order_queues = DB::table('orders as a')
					->select($fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'a.order_id')
					->leftjoin('queue_locations as g', 'g.location_code', '=', 'a.location_code')
					->leftjoin('ref_encounter_types as h', 'h.encounter_code', '=', 'c.encounter_code')
					->where('a.location_code','=',$location)
					->whereNull('cancel_id')
					->where('order_completed', '=', 0)
					->groupBy('a.encounter_id')
					->paginate($this->paginateValue);


			return view('order_queues.index', [
					'order_queues'=>$order_queues,
					'search'=>$request->search,
					'locations' => QueueLocation::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'location'=>$location,
					])
				->withCookie(cookie('queue_location',$location));
	}

}
