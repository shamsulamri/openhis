<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order as AdmissionTask;
use Log;
use DB;
use Session;
use App\Product;
use App\QueueLocation as Location;
use App\Ward;
use App\ProductCategory;
use App\Order;
use App\OrderMultiple;
use App\OrderHelper;
use App\QueueLocation;
use App\Store;
use Auth;
use Route;
use App\DojoUtility;
use App\StockHelper;
use App\EncounterType;

class AdmissionTaskController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			if (empty($request->cookie('ward'))) {
					Session::flash('message', 'Ward not set. Please select your ward.');
					return redirect('/wards');
			}

			if (Auth::user()->authorization->module_support==1) { 
					$ward_code = $request->ward_code; 
			} else {
					$ward_code=$request->cookie('ward');
			}

			if (!empty(Auth::user()->authorization->location_code)) {
				$location_code = Auth::user()->authorization->location_code;
			} else {
				$location_code = $request->cookie('queue_location');
			}
			$location = QueueLocation::find($location_code);

			$encounter_code = $request->cookie('encounter')?:null;

			$sql_select = "
 a.order_id, a.order_completed,order_multiple, a.updated_at, a.created_at,patient_name, patient_mrn, bed_name,a.product_code,product_name,c.patient_id, i.name, ward_name, a.encounter_id,updated_by,cancel_id,category_code,stop_id, product_duration_use,q.id as order_drug_id, a.created_at as order_created,urgency_name,q.frequency_code, case when t.frequency_code = 'STAT' then frequency_index else coalesce(urgency_index,9) end as urgency_index, v.name as order_by
";
			$admission_tasks = DB::table('orders as a')
					->selectRaw($sql_select)
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->leftjoin('products as d', 'd.product_code', '=', 'a.product_code')
					->leftjoin('admissions as e', 'e.encounter_id', '=', 'b.encounter_id')
					->leftjoin('beds as f', 'f.bed_code', '=', 'e.bed_code')
					->leftjoin('wards as g', 'g.ward_code', '=', 'f.ward_code')
					->leftjoin('order_drugs as h', 'h.order_id', '=', 'a.order_id')
					->leftjoin('users as i', 'i.id', '=', 'a.updated_by')
					->leftjoin('order_cancellations as j', 'j.order_id', '=', 'a.order_id')
					->leftjoin('bills as k', 'k.encounter_id','=','b.encounter_id')
					->leftjoin('order_stops as p', 'p.order_id', '=', 'a.order_id')
					->leftjoin('order_drugs as q', 'q.order_id', '=', 'a.order_id')
					->leftjoin('order_investigations as r', 'r.order_id', '=', 'a.order_id')
					->leftjoin('ref_urgencies as s', 's.urgency_code', '=', 'r.urgency_code')
					->leftjoin('drug_frequencies as t', 't.frequency_code', '=', 'q.frequency_code')
					->leftJoin('consultations as u', 'u.consultation_id', '=', 'a.consultation_id')
					->leftjoin('users as v', 'v.id', '=', 'u.user_id')
					->where('a.product_code','<>','consultation_fee')
					->whereNull('k.id')
					->whereNull('cancel_id');

					//->where('b.encounter_code','<>', 'outpatient')
			$admission_tasks= $admission_tasks->orderBy("patient_mrn")
					->orderBy('urgency_index')
					->orderBy("product_name")
					->orderBy('order_created');

			/*
			$admission_tasks = DB::table('orders as a')
					->selectRaw($sql_select)
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->leftjoin('products as d', 'd.product_code', '=', 'a.product_code')
					->leftjoin('admissions as e', 'e.encounter_id', '=', 'b.encounter_id')
					->leftjoin('beds as f', 'f.bed_code', '=', 'e.bed_code')
					->leftjoin('wards as g', 'g.ward_code', '=', 'f.ward_code')
					->leftjoin('order_cancellations as h', 'h.order_id', '=', 'a.order_id')
					->leftjoin('users as i', 'i.id', '=', 'a.updated_by')
					->leftjoin('discharges as j', 'j.encounter_id','=','b.encounter_id')
					->leftjoin('consultations as k', 'k.consultation_id', '=', 'a.consultation_id')
					->leftjoin('order_posts as n', 'n.consultation_id', '=', 'k.consultation_id')
					->leftjoin('ward_discharges as o', 'o.encounter_id', '=', 'b.encounter_id')
					->leftjoin('order_stops as p', 'p.order_id', '=', 'a.order_id')
					->leftjoin('order_drugs as q', 'q.order_id', '=', 'a.order_id')
					->leftjoin('order_investigations as r', 'r.order_id', '=', 'a.order_id')
					->leftjoin('ref_urgencies as s', 's.urgency_code', '=', 'r.urgency_code')
					->leftjoin('drug_frequencies as t', 't.frequency_code', '=', 'q.frequency_code')
					->where('b.encounter_code','<>', 'outpatient')
					->where('a.product_code','<>','consultation_fee')
					->where('d.product_drop_charge','=',0)
					->whereNull('cancel_id')
					->whereNull('o.discharge_id')
					->whereNotNull('n.post_id')
					->orderBy('patient_name')
					->orderBy('urgency_index')
					->orderBy('order_created');

					->where(function ($query) use ($request) {
						$query->where('order_completed','=',0);
							//->orWhere('category_code','=', 'drugs');
					})
			 */
			$order_ids = $admission_tasks->implode('order_id',',');
			
			$admission_tasks = $admission_tasks->paginate($this->paginateValue);
				
			$categories = ProductCategory::select('category_name', 'category_code')
							->whereIn('category_code',$admission_tasks->pluck('category_code'))
							->lists('category_name', 'category_code')
							->sortBy('category_name')
							->prepend('','');

			return view('admission_tasks.index', [
					'admission_tasks'=>$admission_tasks,
					'categories' => $categories, //ProductCategory::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward' => Ward::where('ward_code', $request->cookie('ward'))->first(),
					'locations' => QueueLocation::orderBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'category' => '',
					'order_ids' => $order_ids,
					'show_all' => null,
					'ward_code'=>null,
					'location_code'=>null,
					'location'=>Location::find($location_code),
					'order_helper'=>new OrderHelper(),
					'multiple_ids'=>'',
					'encounter_code'=>$encounter_code,
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
			]);
	}

	public function create()
	{
			$admission_task = new AdmissionTask();
			return view('admission_tasks.create', [
					'admission_task' => $admission_task,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$admission_task = new AdmissionTask();
			$valid = $admission_task->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$admission_task = new AdmissionTask($request->all());
					$admission_task->order_id = $request->order_id;
					$admission_task->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/admission_tasks/id/'.$admission_task->order_id);
			} else {
					return redirect('/admission_tasks/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$admission_task = AdmissionTask::findOrFail($id);
			return view('admission_tasks.edit', [
					'admission_task'=>$admission_task,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'product' => $admission_task->product,
					'patient'=>$admission_task->consultation->encounter->patient,
					'order_helper'=>new OrderHelper(),
					]);
	}

	public function update(Request $request, $id) 
	{
			$admission_task = AdmissionTask::findOrFail($id);
			$admission_task->fill($request->input());

			$admission_task->order_completed = $request->order_completed ?: 0;
			$admission_task->order_is_discharge = $request->order_is_discharge ?: 0;

			$valid = $admission_task->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$admission_task->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/admission_tasks');
			} else {
					return view('admission_tasks.edit', [
							'admission_task'=>$admission_task,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$admission_task = AdmissionTask::findOrFail($id);
		return view('admission_tasks.destroy', [
			'admission_task'=>$admission_task
			]);

	}
	public function destroy($id)
	{	
			AdmissionTask::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/admission_tasks');
	}
	
	public function search(Request $request)
	{
			if (Auth::user()->authorization->module_support==1) { 
					$ward_code = $request->ward_code; 
			} else {
					$ward_code=$request->cookie('ward');
			}

			//$location_code = $request->cookie('queue_location');

			if (!empty(Auth::user()->authorization->location_code)) {
				$location_code = Auth::user()->authorization->location_code;
			} else {
				$location_code = $request->cookie('queue_location');
			}

			$location = QueueLocation::find($location_code);

			$encounter_code = $request->encounter_code;

			$sql_select = "
 a.order_id, a.order_completed,order_multiple, a.updated_at, a.created_at,patient_name, patient_mrn, bed_name,a.product_code,product_name,c.patient_id, i.name, ward_name, a.encounter_id,updated_by,cancel_id,category_code,stop_id, product_duration_use,q.id as order_drug_id, a.created_at as order_created,urgency_name,q.frequency_code, case when t.frequency_code = 'STAT' then frequency_index else coalesce(urgency_index,9) end as urgency_index, v.name as order_by, v.location_code
";
			$admission_tasks = DB::table('orders as a')
					->selectRaw($sql_select)
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->leftjoin('products as d', 'd.product_code', '=', 'a.product_code')
					->leftjoin('admissions as e', 'e.encounter_id', '=', 'b.encounter_id')
					->leftjoin('beds as f', 'f.bed_code', '=', 'e.bed_code')
					->leftjoin('wards as g', 'g.ward_code', '=', 'f.ward_code')
					->leftjoin('order_drugs as h', 'h.order_id', '=', 'a.order_id')
					->leftjoin('users as i', 'i.id', '=', 'a.updated_by')
					->leftjoin('order_cancellations as j', 'j.order_id', '=', 'a.order_id')
					->leftjoin('bills as k', 'k.encounter_id','=','b.encounter_id')
					->leftjoin('order_stops as p', 'p.order_id', '=', 'a.order_id')
					->leftjoin('order_drugs as q', 'q.order_id', '=', 'a.order_id')
					->leftjoin('order_investigations as r', 'r.order_id', '=', 'a.order_id')
					->leftjoin('ref_urgencies as s', 's.urgency_code', '=', 'r.urgency_code')
					->leftjoin('drug_frequencies as t', 't.frequency_code', '=', 'q.frequency_code')
					->leftJoin('consultations as u', 'u.consultation_id', '=', 'a.consultation_id')
					->leftjoin('users as v', 'v.id', '=', 'u.user_id')
					->where('a.product_code','<>','consultation_fee')
					->whereNull('k.id')
					->whereNull('cancel_id');

			$categories = ProductCategory::select('category_name', 'category_code')
							->whereIn('category_code',$admission_tasks->pluck('category_code'))
							->lists('category_name', 'category_code')
							->sortBy('category_name')
							->prepend('','');

			if ($request->categories) {
				$admission_tasks = $admission_tasks->where('d.category_code','like', '%'.$request->categories.'%');
			}

			if ($request->location_code) {
					$admission_tasks = $admission_tasks->where('v.location_code', '=', $request->location_code);
			}

			if ($request->ward_code) {
					$admission_tasks = $admission_tasks->where('f.ward_code', '=', $ward_code);
			}


			//->whereNull('discharge_id')
			
			/**
			if (Auth::user()->authorization->module_support==1) {
					$admission_tasks = $admission_tasks->where('a.location_code', '=', $location_code);
					if (!empty($ward_code)) { 
						$admission_tasks = $admission_tasks->where('f.ward_code', '=', $ward_code);
					}
			} else {
					$admission_tasks = $admission_tasks->where('f.ward_code', '=', $ward_code);
			}
			**/

			/**
			if (empty($request->show_all)) {
					$admission_tasks = $admission_tasks->where('order_completed','=',0);
			}
			**/
					
			$order_ids = $admission_tasks->implode('order_id',',');

			$admission_tasks= $admission_tasks->orderBy("patient_mrn")
											->orderBy('urgency_index')
											->orderBy("product_name")
											->orderBy('order_created')
											->paginate($this->paginateValue);

			return view('admission_tasks.index', [
					'admission_tasks'=>$admission_tasks,
					'ward' => Ward::where('ward_code', $request->cookie('ward'))->first(),
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'categories' => $categories,
					'locations' => QueueLocation::orderBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'category' => $request->categories,
					'order_ids' => $order_ids,
					'show_all' => $request->show_all,
					'ward_code'=>$request->ward_code,
					'location_code'=>$request->location_code,
					'location'=>Location::find($location_code),
					'order_helper'=>new OrderHelper(),
					'multiple_ids'=>'',
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'encounter_code'=>$encounter_code,
			])->withCookie(cookie('encounter',$encounter_code, 2628000));
	}

	public function searchById($id)
	{
			$admission_tasks = DB::table('orders')
					->where('order_id','=',$id)
					->paginate($this->paginateValue);

			return view('admission_tasks.index', [
					'admission_tasks'=>$admission_tasks
			]);
	}

	public function status(Request $request)
	{
			$store_code = "main";
			if (Auth::user()->authorization->module_support==1) { 
					$location_code = $request->cookie('queue_location');
					$location = Location::find($location_code);
					$store_code = $location->store_code;
			} else {
					$ward_code=$request->cookie('ward');
					$ward = Ward::find($ward_code);
					if (!empty($ward->store_code)) {
							$store_code = $ward->store_code;
					}
			}

			$ids = explode(";",$request->ids);

			foreach($ids as $id) {

					$order = Order::find($id);

					$name = "order:".$id;
					if ($order) {
					Log::info($name);
									$order->order_completed=$request->$name?1:0;
									$order->completed_at = $request->$name==1?DojoUtility::dateTimeWriteFormat(DojoUtility::now()):null;
									$order->updated_by = $request->$name==1?Auth::user()->id:null;
									$order->store_code = $store_code;
									$order->location_code = null;
									$order->save();
									Log::info($order->order_completed);
					}
					/*
					else {
							if ($order->order_completed==1) $order->updated_by = Auth::user()->id;
							$order->order_completed=0;
					}
					 */
			}

			/** Multiple orders **/
			$multis = explode(",",$request->multiple_ids);

			foreach($multis as $multi) {
					$multiple_order = OrderMultiple::find($multi);

					$name = "multi:".$multi;
					if (!empty($multiple_order)) {
							if ($request->$name==1) {
									$multiple_order->order_completed=1;
									$multiple_order->updated_by = Auth::user()->id;
									$multiple_order->store_code = $store_code;
									$multiple_order->save();
									$multiple_completed = OrderMultiple::where('order_id','=', $multiple_order->order_id);

									$count_total = $multiple_completed->count();
									$count_completed = $multiple_completed->where('order_completed',1)->count();
									Log::info("Count order:".$count_total);
									Log::info("Count order:".$count_completed);

									if ($count_total==$count_completed) {
											$parent_order = Order::find($multiple_order->order_id);
											$parent_order->order_completed=1;
											$parent_order->updated_by = Auth::user()->id;
											$parent_order->store_code = $store_code;
											$parent_order->save();
									}
							} 
					}
					/*
					else {
							if ($order->order_completed==1) $order->updated_by = Auth::user()->id;
							$order->order_completed=0;
					}
					 */
			}

			Session::flash('message', 'Record successfully updated.');
			return redirect('/admission_tasks');
			//return redirect()->action('AdmissionTaskController@search', $request);
	}

}
