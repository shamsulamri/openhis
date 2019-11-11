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
use App\EncounterType;
use Carbon\Carbon;
use Auth;
use App\Order;
use App\ProductCategory;
use Gate;
use App\OrderHelper;
use App\Encounter;
use App\EncounterHelper;
use App\DojoUtility;

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
			$location_code = Auth::user()->defaultLocation($request);
			if (empty($location_code)) {
					return redirect('queue_locations');
			}

			$location = QueueLocation::find($location_code);

			//$queue_encounters = $request->cookie('queue_encounters');
			//$queue_categories = $request->cookie('queue_categories');

			$queue_encounters = explode(';',Auth::user()->authorization->queue_encounters);
			$queue_categories = explode(';',Auth::user()->authorization->queue_categories);

			$fields = ['a.order_id',
					'patient_name', 
					'patient_mrn',	
					'encounter_name', 
					'c.encounter_id',	
					'g.location_code',
					'cancel_id',
					'a.created_at',
					'bed_name',
					'k.location_name',
					'o.id as bill_id',
					'investigation_date',
					'ward_name',
					];

			/**
			$order_queues = DB::table('orders as a')
					->select($fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'a.order_id')
					->leftjoin('queue_locations as g', 'g.location_code', '=', 'a.location_code')
					->leftjoin('ref_encounter_types as h', 'h.encounter_code', '=', 'c.encounter_code')
					->leftjoin('admissions as i', 'i.encounter_id', '=', 'c.encounter_id')
					->leftjoin('beds as l', 'l.bed_code', '=', 'i.bed_code')
					->leftjoin('queues as j', 'j.encounter_id', '=', 'c.encounter_id')
					->leftjoin('queue_locations as k', 'k.location_code', '=', 'j.location_code')
					->leftjoin('order_investigations as m', 'm.order_id', '=', 'a.order_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'b.consultation_id')
					->leftjoin('bills as o','o.encounter_id', '=', 'c.encounter_id')
					->leftjoin('wards as p','p.ward_code', '=', 'l.ward_code')
					->where('investigation_date','<=', Carbon::today())
					->where('a.location_code','=',$location_code)
					//->where('c.encounter_code', '!=', 'inpatient')
					->whereNull('cancel_id')
					->whereNotNull('n.post_id')
					->groupBy('a.encounter_id');
			**/


			/** 2019/10/11 **/
			/**
			$order_queues = Order::groupBy('orders.encounter_id')
					->leftjoin('consultations as b', 'b.consultation_id', '=', 'orders.consultation_id')
					->leftjoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'orders.order_id')
					->leftjoin('order_investigations as m', 'm.order_id', '=', 'orders.order_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'b.consultation_id')
					->leftjoin('products as o', 'o.product_code', '=', 'orders.product_code')
					->whereIn('o.category_code', $queue_categories)
					->whereIn('c.encounter_code', $queue_encounters)
					->where('order_completed','=',0)
					->whereNull('cancel_id')
					->whereNotNull('n.post_id')
					->whereNull('c.deleted_at')
					->orderBy('b.created_at', 'desc');
			**/

			$order_queues = Order::groupBy('orders.encounter_id')
					->leftjoin('encounters as c', 'c.encounter_id', '=', 'orders.encounter_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'orders.order_id')
					->leftjoin('order_investigations as m', 'm.order_id', '=', 'orders.order_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'orders.consultation_id')
					->leftjoin('products as o', 'o.product_code', '=', 'orders.product_code')
					->leftjoin('ref_encounter_types as p', 'p.encounter_code', '=', 'c.encounter_code')
					->whereIn('o.category_code', $queue_categories)
					->whereIn('c.encounter_code', $queue_encounters)
					->where('order_completed','=',0)
					->whereNull('cancel_id')
					->whereNotNull('n.post_id')
					->whereNull('c.deleted_at')
					->orderBy('orders.created_at', 'desc');

			if ($request->future) {
					$order_queues = $order_queues->where('order_is_future','=', 1);
			} else {
					$order_queues = $order_queues->where('order_is_future','=', 0);
			}

			$order_queues = $order_queues->paginate($this->paginateValue);

			$futures = Order::groupBy('orders.encounter_id')
					->leftjoin('consultations as b', 'b.consultation_id', '=', 'orders.consultation_id')
					->leftjoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'orders.order_id')
					->leftjoin('order_investigations as m', 'm.order_id', '=', 'orders.order_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'b.consultation_id')
					->leftjoin('products as o', 'o.product_code', '=', 'orders.product_code')
					->whereIn('o.category_code', $queue_categories)
					->whereIn('c.encounter_code', $queue_encounters)
					->where('order_completed','=',0)
					->whereNull('cancel_id')
					->whereNotNull('n.post_id')
					->whereNull('c.deleted_at')
					->where('order_is_future','=', 1)
					->where('investigation_date', '=', DojoUtility::todayYMD())
					->orderBy('b.created_at', 'desc')
					->get();

			$locations = QueueLocation::orderBy('location_name')->lists('location_name', 'location_code')->prepend('','');
			$status = array(''=>'','incomplete'=>'Incomplete', 'completed'=>'Completed');

			$is_future = null;
			if (!empty($request->future)) $is_future=true;

			$count = $request->count ?: 0;

			return view('order_queues.index', [
					'order_queues'=>$order_queues,
					'search'=>$request->search,
					'locations' => QueueLocation::whereNull('encounter_code')->orderBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'encounters' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'location'=>$location,
					'encounter_code'=>null,
					'is_future'=>$is_future,
					'count'=>$count,
					'status'=>$status,
					'status_code'=>null,
					'queue_encounters'=>$queue_encounters,
					'queue_categories'=>$queue_categories,
					'location_code'=>$location_code,
					'helper'=> new OrderHelper(),
					'encounter_helper'=> new EncounterHelper(),
					'future_count'=>count($futures),
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
	
	public function search2(Request $request)
	{
			$location_code = $request->cookie('queue_location');
			$location = QueueLocation::find($location_code);

			$fields = ['a.order_id',
					'patient_name', 
					'patient_mrn',	
					'encounter_name', 
					'c.encounter_id',	
					'g.location_code',
					'cancel_id',
					'a.created_at',
					'bed_name',
					'k.location_name',
					'o.id as bill_id',
					];

			$order_queues = DB::table('orders as a')
					->select($fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'a.order_id')
					->leftjoin('queue_locations as g', 'g.location_code', '=', 'a.location_code')
					->leftjoin('ref_encounter_types as h', 'h.encounter_code', '=', 'c.encounter_code')
					->leftjoin('admissions as i', 'i.encounter_id', '=', 'c.encounter_id')
					->leftjoin('beds as l', 'l.bed_code', '=', 'i.bed_code')
					->leftjoin('queues as j', 'j.encounter_id', '=', 'c.encounter_id')
					->leftjoin('queue_locations as k', 'k.location_code', '=', 'j.location_code')
					->leftjoin('order_investigations as m', 'm.order_id', '=', 'a.order_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'b.consultation_id')
					->leftjoin('bills as o','o.encounter_id', '=', 'c.encounter_id')
					->where('investigation_date','<=', Carbon::today())
					->where('a.location_code','=',$location_code)
					->whereNull('cancel_id')
					->where('order_completed', '=', 0)
					->whereNotNull('n.post_id')
					->groupBy('a.encounter_id');

			if (!empty($request->encounter_code)) {
				$order_queues = $order_queues->where('c.encounter_code','=', $request->encounter_code);
			}
			$order_queues = $order_queues->paginate($this->paginateValue);

			return view('order_queues.index', [
					'order_queues'=>$order_queues,
					'search'=>$request->search,
					'locations' => QueueLocation::whereNull('encounter_code')->orderBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'location'=>$location,
					'encounters' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'encounter_code'=>$request->encounter_code,
					])
				->withCookie(cookie('queue_location',$location));
	}

	public function search(Request $request)
	{
			/*
			if (empty($request->cookie('queue_location'))) {
					return redirect('queue_locations');
			}
			 */

			if (!empty(Auth::user()->authorization->location_code)) {
				$location_code = Auth::user()->authorization->location_code;
			} else {
				$location_code = $request->cookie('queue_location');
			}
			$location = QueueLocation::find($location_code);

			/*
			$queue_encounters = $request->cookie('queue_encounters');
			$queue_categories = $request->cookie('queue_categories');
			 */

			$queue_encounters = explode(';',Auth::user()->authorization->queue_encounters);
			$queue_categories = explode(';',Auth::user()->authorization->queue_categories);

			/*
			$fields = ['a.order_id',
					'patient_name', 
					'patient_mrn',	
					'encounter_name', 
					'c.encounter_id',	
					'g.location_code',
					'cancel_id',
					'a.created_at',
					'bed_name',
					'k.location_name',
					'o.id as bill_id',
					'investigation_date',
					'ward_name',
					];

			$order_queues = DB::table('orders as a')
					->select($fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'a.order_id')
					->leftjoin('queue_locations as g', 'g.location_code', '=', 'a.location_code')
					->leftjoin('ref_encounter_types as h', 'h.encounter_code', '=', 'c.encounter_code')
					->leftjoin('admissions as i', 'i.encounter_id', '=', 'c.encounter_id')
					->leftjoin('beds as l', 'l.bed_code', '=', 'i.bed_code')
					->leftjoin('queues as j', 'j.encounter_id', '=', 'c.encounter_id')
					->leftjoin('queue_locations as k', 'k.location_code', '=', 'j.location_code')
					->leftjoin('order_investigations as m', 'm.order_id', '=', 'a.order_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'b.consultation_id')
					->leftjoin('bills as o','o.encounter_id', '=', 'c.encounter_id')
					->leftjoin('wards as p','p.ward_code', '=', 'l.ward_code')
					->where('investigation_date','<=', Carbon::today())
					->where('a.location_code','=',$location_code)
					->whereNull('cancel_id')
					//->where('c.encounter_code', '!=', 'inpatient')
					->whereNotNull('n.post_id')
					->where('c.encounter_code','=', $request->encounter_code)
					->groupBy('a.encounter_id');
			 */

			/*
			$order_queues = Order::groupBy('orders.encounter_id')
					->leftjoin('consultations as b', 'b.consultation_id', '=', 'orders.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'orders.order_id')
					->leftjoin('order_investigations as m', 'm.order_id', '=', 'orders.order_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'b.consultation_id')
					->leftjoin('products as o', 'o.product_code', '=', 'orders.product_code')
					->leftjoin('patients as p', 'p.patient_id', '=', 'c.patient_id')
					->whereIn('o.category_code', $queue_categories)
					->whereIn('c.encounter_code', $queue_encounters)
					->whereNull('cancel_id')
					->whereNotNull('n.post_id')
					->whereNull('c.deleted_at')
					->orderBy('orders.order_id', 'desc');
			 */

			$order_queues = Order::groupBy('orders.encounter_id')
					->leftjoin('encounters as c', 'c.encounter_id', '=', 'orders.encounter_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'orders.order_id')
					->leftjoin('order_investigations as m', 'm.order_id', '=', 'orders.order_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'orders.consultation_id')
					->leftjoin('products as o', 'o.product_code', '=', 'orders.product_code')
					->leftjoin('ref_encounter_types as p', 'p.encounter_code', '=', 'c.encounter_code')
					->leftjoin('patients as q', 'q.patient_id', '=', 'c.patient_id')
					->whereIn('o.category_code', $queue_categories)
					->whereIn('c.encounter_code', $queue_encounters)
					->whereNull('cancel_id')
					->whereNotNull('n.post_id')
					->whereNull('c.deleted_at')
					->orderBy('orders.created_at', 'desc');

			if (!empty($request->encounter_code)) {
					$order_queues = $order_queues->where('c.encounter_code','=', $request->encounter_code);
			}

			if ($request->status_code =='incomplete' or empty($request->status_code)) {
					$order_queues = $order_queues->where('order_completed', '=', 0);
			} 

			if ($request->status_code =='unreported') {
					$order_queues = $order_queues->whereNull('order_report');
			} 
			if ($request->status_code =='completed') {
					$order_queues = $order_queues->where('order_completed', '=', 1);
			} 

			if ($request->discharge) {
					$order_queues = $order_queues->leftjoin('discharges as q', 'q.encounter_id', '=', 'orders.encounter_id')
									->whereNotNull('discharge_id')
									->where('investigation_date','>=', Carbon::today());
			} else {
					//$order_queues = $order_queues->where('investigation_date','<=', Carbon::today());
			}

			if (!empty($request->search)) {

					$order_queues = $order_queues->where(function ($query) use ($request) {
							$query->where('patient_mrn','like','%'.$request->search.'%')
								->orWhere('patient_name', 'like','%'.$request->search.'%');
					});
			}

			$order_queues = $order_queues->paginate($this->paginateValue);

			$futures = Order::groupBy('orders.encounter_id')
					->leftjoin('consultations as b', 'b.consultation_id', '=', 'orders.consultation_id')
					->leftjoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'orders.order_id')
					->leftjoin('order_investigations as m', 'm.order_id', '=', 'orders.order_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'b.consultation_id')
					->leftjoin('products as o', 'o.product_code', '=', 'orders.product_code')
					->whereIn('o.category_code', $queue_categories)
					->whereIn('c.encounter_code', $queue_encounters)
					->where('order_completed','=',0)
					->whereNull('cancel_id')
					->whereNotNull('n.post_id')
					->whereNull('c.deleted_at')
					->where('order_is_future','=', 1)
					->where('investigation_date', '=', DojoUtility::todayYMD())
					->orderBy('b.created_at', 'desc')
					->get();

			$status = array(''=>'','incomplete'=>'Incomplete', 'completed'=>'Completed', 'unreported'=>'Unreported');
			$locations = QueueLocation::orderBy('location_name')->lists('location_name', 'location_code')->prepend('','');
			//return $locations;
			
			$is_future = null;
			if (!empty($request->future)) $is_future=true;

			return view('order_queues.index', [
					'order_queues'=>$order_queues,
					'search'=>$request->search,
					'encounter_code'=>$request->encounter_code,
					'locations' => QueueLocation::whereNull('encounter_code')->orderBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'encounters' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'location'=>$location,
					'encounter_code'=>null,
					'is_discharge'=>$request->discharge,
					'count'=>$request->count,
					'status'=>$status,
					'status_code'=>$request->status_code,
					'is_future'=>$is_future,
					'location_code'=>$request->location_code,
					'future_count'=>count($futures),
					'encounter_helper'=>new EncounterHelper(),
					]);
	}

	public function setting(Request $request) {
			$queue_encounters = $request->cookie('queue_encounters');
			$queue_categories = $request->cookie('queue_categories');
			return view('order_queues.setup', [
					'encounters' => EncounterType::all()->sortBy('encounter_name'),
					'categories' => ProductCategory::all()->sortBy('category_name'),
					'queue_encounters' => $queue_encounters,
					'queue_categories' => $queue_categories,
			]);
	}

	public function setup(Request $request) {
			$encounters = EncounterType::all();
			$categories = ProductCategory::all();

			$queue_encounters = [];
			foreach ($encounters as $encounter) {
					if ($request[$encounter->encounter_code]) {
						array_push($queue_encounters, $encounter->encounter_code);
					}
			}

			$queue_categories = [];
			foreach ($categories as $category) {
					if ($request[$category->category_code]) {
						array_push($queue_categories, $category->category_code);
					}
			}

			return redirect('/order_queues')
				->withCookie(cookie('queue_encounters',$queue_encounters, 2628000))
				->withCookie(cookie('queue_categories',$queue_categories, 2628000));
	}

	public function report(Request $request) {
			$location_code = Auth::user()->defaultLocation($request);
			$location = QueueLocation::find($location_code);
			$queue_encounters = explode(';',Auth::user()->authorization->queue_encounters);
			$queue_categories = explode(';',Auth::user()->authorization->queue_categories);

			$orders = Order::select('orders.order_id')
					->leftjoin('consultations as b', 'b.consultation_id', '=', 'orders.consultation_id')
					->leftjoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftjoin('order_cancellations as e', 'e.order_id', '=', 'orders.order_id')
					->leftjoin('order_investigations as m', 'm.order_id', '=', 'orders.order_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'b.consultation_id')
					->leftjoin('products as o', 'o.product_code', '=', 'orders.product_code')
					->leftjoin('patients as p', 'p.patient_id', '=', 'c.patient_id')
					->whereIn('c.encounter_code', $queue_encounters)
					->whereIn('o.category_code', $queue_categories)
					->where('order_completed','=',1)
					->where('category_code','=','imaging2')
					->whereNull('cancel_id')
					->whereNotNull('n.post_id')
					->distinct('orders.order_id')
					->orderBy('c.created_at', 'desc');

			$locations = QueueLocation::orderBy('location_name')->lists('location_name', 'location_code')->prepend('','');
			$status = array(''=>'','incomplete'=>'Incomplete', 'completed'=>'Completed');

			$count = $request->count ?: 0;

			if (!empty($request->encounter_code)) {
					$orders = $orders->where('c.encounter_code','=', $request->encounter_code);
			}

			if ($request->status_code =='incomplete') {
					$orders = $orders->whereNull('order_report');
			} 
			if ($request->status_code =='completed') {
					$orders = $orders->whereNotNull('order_report');
			} 

			if (!empty($request->search)) {
					$orders = $orders->where(function ($query) use ($request) {
							$query->where('patient_mrn','like','%'.$request->search.'%')
								->orWhere('patient_name', 'like','%'.$request->search.'%');
					});
			}

			$orders = $orders->paginate($this->paginateValue);

			$helper = new OrderHelper();
			//$helper->removeDuplicate();

			return view('order_queues.report', [
					'orders'=>$orders,
					'search'=>$request->search,
					'locations' => QueueLocation::whereNull('encounter_code')->orderBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'encounters' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'location'=>$location,
					'encounter_code'=>null,
					'count'=>$count,
					'status'=>$status,
					'status_code'=>null,
					'queue_encounters'=>$queue_encounters,
					'queue_categories'=>$queue_categories,
					'location_code'=>$location_code,
					'helper'=> new OrderHelper(),
					'encounter_helper'=> new EncounterHelper(),
					'status_code'=>$request->status_code,
					'search'=>$request->search,
					'encounter_code'=>$request->encounter_code,
					]);
	}
}
