<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order as OrderTask;
use Log;
use DB;
use Session;
use App\Product;
use App\QueueLocation as Location;
use App\Encounter;
use App\Consultation;
use App\Http\Controllers\ProductController;
use App\Store;
use App\Order;

class OrderTaskController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$fields = ['patient_name', 'product_name', 'a.product_code', 'cancel_id', 'a.order_id', 'a.post_id', 'a.created_at','order_is_discharge',
					'location_name',	
					'cancel_id',
					'j.created_at',
					'k.name',
					'order_completed',
					];
			$order_tasks = DB::table('orders as a')
					->select($fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->join('products as e','e.product_code','=','a.product_code')
					->leftjoin('order_cancellations as f', 'f.order_id', '=', 'a.order_id')
					->leftjoin('product_categories as g', 'g.category_code', '=', 'e.category_code')
					->leftjoin('queues as h', 'h.encounter_id', '=', 'c.encounter_id')
					->leftjoin('queue_locations as i', 'i.location_code', '=', 'h.location_code')
					->leftjoin('order_posts as j', 'j.post_id', '=', 'a.post_id')
					->leftjoin('users as k','k.id','=', 'a.user_id')
					->where('a.post_id','>',0)
					->where('c.encounter_id','=', $encounter_id)
					->whereNull('cancel_id')
					->orderBy('a.post_id')
					->orderBy('a.created_at')
					->orderBy('order_is_discharge','desc')
					->orderBy('a.created_at', 'desc')
					->paginate($this->paginateValue);

			return view('order_tasks.index', [
					'order_tasks'=>$order_tasks,
			]);
	}

	public function task(Request $request, $encounter_id, $location_code)
	{
			Session::set('encounter_id', $encounter_id);
			$encounter = Encounter::find($encounter_id);

			$location_code = $request->cookie('queue_location');
			$location = Location::find($location_code);

			$consultation = Consultation::where('patient_id','=',$encounter->patient_id)
					->orderBy('created_at','desc')
					->first();
			Session::set('consultation_id', $consultation->consultation_id);

			$fields = ['patient_name', 'product_name', 'a.product_code', 'cancel_id', 'a.order_id', 'a.post_id', 'a.created_at','order_is_discharge',
					'i.location_name',	
					'cancel_id',
					'order_quantity_request',
					'a.created_at',
					'k.name',
					'order_completed',
					'name',
					];
			$order_tasks = DB::table('orders as a')
					->select($fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->join('products as e','e.product_code','=','a.product_code')
					->leftjoin('order_cancellations as f', 'f.order_id', '=', 'a.order_id')
					->leftjoin('product_categories as g', 'g.category_code', '=', 'e.category_code')
					->leftjoin('queues as h', 'h.encounter_id', '=', 'c.encounter_id')
					->leftjoin('queue_locations as i', 'i.location_code', '=', 'h.location_code')
					->leftjoin('order_posts as j', 'j.post_id', '=', 'a.post_id')
					->leftjoin('users as k','k.id','=', 'a.user_id')
					->where('c.encounter_id','=', $encounter_id)
					->where('a.location_code','=',$location_code)
					->orderBy('cancel_id')
					->orderBy('a.post_id')
					->orderBy('a.created_at')
					->orderBy('order_is_discharge','desc')
					->orderBy('a.created_at', 'desc')
					->paginate($this->paginateValue);
			
			$ids='';
			foreach ($order_tasks as $task) {
					$ids .= (string)$task->order_id.",";
			}
			
			return view('order_tasks.index', [
					'order_tasks'=>$order_tasks,
					'patient'=>$encounter->patient,
					'encounter'=>$encounter,
					'encounter_id' => $encounter_id,
					'ids'=>$ids,
					'location'=>$location,
					'product'=> new Product(),
			]);
	}
	public function edit($id) 
	{
			$order_task = OrderTask::findOrFail($id);

			return view('order_tasks.edit', [
					'order_task'=>$order_task,
					'product' => $order_task->product,
					'patient'=>$order_task->consultation->encounter->patient,
					'encounter'=>$order_task->consultation->encounter,
					'encounter_id' => $order_task->consultation->encounter_id,
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_task = OrderTask::findOrFail($id);
			$order_task->fill($request->input());

			$order_task->order_completed = $request->order_completed ?: 0;
			$valid = $order_task->validate($request->all(), $request->_method);	
			
			

			if ($valid->passes()) {
					$order_task->save();
					$productController = new ProductController();
					$productController->updateTotalOnHand($order_task->product_code);
					Session::flash('message', 'Record successfully updated.');
					if ($request->user()->can('module-support')) {
						return redirect('/order_tasks/task/'.$order_task->consultation->encounter->encounter_id.'/'.$order_task->product->location_code);
					} else {
						return redirect('/admission_tasks');
					}
			} else {
					return view('order_tasks.edit', [
							'order_task'=>$order_task,
							'product' => $order_task->product,
							'consultation'=>$order_task->consultation,
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_task = OrderTask::findOrFail($id);
		return view('order_tasks.destroy', [
			'order_task'=>$order_task
			]);

	}
	public function destroy($id)
	{	
			OrderTask::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_tasks');
	}
	
	public function search(Request $request)
	{
			$order_tasks = DB::table('orders')
					->where('product_code','like','%'.$request->search.'%')
					->orWhere('order_id', 'like','%'.$request->search.'%')
					->orderBy('product_code')
					->paginate($this->paginateValue);

			return view('order_tasks.index', [
					'order_tasks'=>$order_tasks,
					'search'=>$request->search
					]);
	}

	public function status(Request $request)
	{
			$values = explode(',',$request->ids);

			foreach ($values as $orderId) {
					$value = $request->$orderId ?: 0;
					if ($orderId>0) {
							OrderTask::where('order_id', $orderId)
								->update(['order_completed'=>$value]);				
							$order = OrderTask::find($orderId);
							$productController = new ProductController();
							$productController->updateTotalOnHand($order->product_code);
					}
			}
			$order_task = OrderTask::find($values[0]);
			Session::flash('message', 'Record successfully updated.');
			return redirect('order_queues');
			//return redirect('/order_tasks/task/'.$order_task->consultation->consultation_id.'/'.$order_task->product->category->location_code);
	}
}
