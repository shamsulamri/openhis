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
use Auth;

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

			$admission_tasks = DB::table('orders as a')
					->select('a.order_id', 'a.order_completed', 'a.updated_at', 'a.created_at','patient_name', 'patient_mrn', 'bed_name','a.product_code','product_name','c.patient_id', 'i.name')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->leftjoin('products as d', 'd.product_code', '=', 'a.product_code')
					->leftjoin('admissions as e', 'e.encounter_id', '=', 'b.encounter_id')
					->leftjoin('beds as f', 'f.bed_code', '=', 'e.bed_code')
					->leftjoin('wards as g', 'g.ward_code', '=', 'f.ward_code')
					->leftjoin('order_cancellations as h', 'h.order_id', '=', 'a.order_id')
					->leftjoin('users as i', 'i.id', '=', 'a.updated_by')
					->where('b.encounter_code','<>', 'outpatient')
					->where('order_completed','=',0)
					->whereNull('cancel_id')
					->orderBy('product_name')
					->orderBy('bed_name');

			$order_ids = $admission_tasks->implode('order_id',',');
			
			$admission_tasks = $admission_tasks->paginate($this->paginateValue);

			$ward = $request->cookie('ward');
			return view('admission_tasks.index', [
					'admission_tasks'=>$admission_tasks,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'categories' => ProductCategory::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'ward' => $request->cookie('ward'),
					'category' => '',
					'group_by' => 'order',
					'order_ids' => $order_ids,
					'show_all' => null,
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
			$ward=$request->cookie('ward');

			$admission_tasks = DB::table('orders as a')
					->select('a.order_id', 'a.order_completed', 'a.updated_at', 'a.created_at','patient_name', 'patient_mrn', 'bed_name','a.product_code','product_name','c.patient_id', 'i.name')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->leftjoin('products as d', 'd.product_code', '=', 'a.product_code')
					->leftjoin('admissions as e', 'e.encounter_id', '=', 'b.encounter_id')
					->leftjoin('beds as f', 'f.bed_code', '=', 'e.bed_code')
					->leftjoin('wards as g', 'g.ward_code', '=', 'f.ward_code')
					->leftjoin('order_drugs as h', 'h.order_id', '=', 'a.order_id')
					->leftjoin('users as i', 'i.id', '=', 'a.updated_by')
					->leftjoin('order_cancellations as j', 'j.order_id', '=', 'a.order_id')
					->where('b.encounter_code','<>', 'outpatient')
					->where('f.ward_code','=',$ward)
					->where('d.category_code','like', '%'.$request->categories.'%')
					->whereNull('cancel_id');

			if (empty($request->show_all)) {
					$admission_tasks = $admission_tasks->where('order_completed','=',0);
			}
					
			$order_ids = $admission_tasks->implode('order_id',',');

			switch($request->group_by) {
				case "order":
					$admission_tasks= $admission_tasks->orderBy("product_name")
													->orderBy("bed_name")
													->paginate($this->paginateValue);
					break;
				default: 
					$admission_tasks= $admission_tasks->orderBy("bed_name")
													->orderBy("product_name")
													->paginate($this->paginateValue);
					break;
			}

			return view('admission_tasks.index', [
					'admission_tasks'=>$admission_tasks,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward' => $request->cookie('ward'),
					'categories' => ProductCategory::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'category' => $request->categories,
					'group_by' => $request->group_by,
					'order_ids' => $order_ids,
					'show_all' => $request->show_all,
			]);
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
			
			$ids = explode(",",$request->completed_ids);

			foreach($ids as $id) {
					$order = Order::find($id);
					if ($request->$id==1) {
							$order->order_completed=1;
							$order->updated_by = Auth::user()->id;
					} else {
							if ($order->order_completed==1) $order->updated_by = Auth::user()->id;
							$order->order_completed=0;
					}
					$order->save();
					Log::info($request->$id);
			}
			Session::flash('message', 'Record updated.');
			return redirect('/admission_tasks');
	}
}
