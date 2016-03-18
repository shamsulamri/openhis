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
use App\Consultation;


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
					->leftjoin('users as k','k.id','=', 'b.user_id')
					->where('a.post_id','>',0)
					->whereNull('cancel_id')
					->orderBy('a.post_id')
					->orderBy('a.created_at')
					->orderBy('order_is_discharge','desc')
					->orderBy('a.created_at', 'desc')
					->paginate($this->paginateValue);

			return view('order_tasks.index', [
					'order_tasks'=>$order_tasks
			]);
	}

	public function task($consultation_id, $location_code)
	{
			$consultation = Consultation::find($consultation_id);
					
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
					->leftjoin('users as k','k.id','=', 'b.user_id')
					->where('a.post_id','>',0)
					->where('g.location_code','=',$location_code)
					->where('a.consultation_id','=', $consultation_id)
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
					'consultation'=>$consultation,
					'ids'=>$ids,
			]);
	}
	public function edit($id) 
	{
			$order_task = OrderTask::findOrFail($id);
			return view('order_tasks.edit', [
					'order_task'=>$order_task,
					'product' => $order_task->product,
					'consultation'=>$order_task->consultation,
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
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_tasks/task/'.$order_task->consultation->consultation_id.'/'.$order_task->product->category->location_code);
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
					OrderTask::where('order_id', $orderId)
						->update(['order_completed'=>$value]);				
			}
			$order_task = OrderTask::find($values[0]);
			Session::flash('message', 'Record successfully updated.');
			return redirect('/order_tasks/task/'.$order_task->consultation->consultation_id.'/'.$order_task->product->category->location_code);
	}
}
