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
use Carbon\Carbon;
use App\Stock;
use Auth;
use App\StockHelper;
use App\StockBatch;
use App\OrderDrug;
use App\OrderHelper;
use App\DojoUtility;

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
					'investigation_date',
					];
			$order_tasks = DB::table('orders as a')
					->select($fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->join('products as e','e.product_code','=','a.product_code')
					->join('order_investigations as f','f.order_id','=','a.order_id')
					->leftjoin('order_cancellations as f', 'f.order_id', '=', 'a.order_id')
					->leftjoin('product_categories as g', 'g.category_code', '=', 'e.category_code')
					->leftjoin('queues as h', 'h.encounter_id', '=', 'c.encounter_id')
					->leftjoin('queue_locations as i', 'i.location_code', '=', 'h.location_code')
					->leftjoin('order_posts as j', 'j.post_id', '=', 'a.post_id')
					->leftjoin('users as k','k.id','=', 'a.user_id')
					->where('a.post_id','>',0)
					->where('c.encounter_id','=', $encounter_id)
					->whereNull('cancel_id')
					->where('investigation_date','<', Carbon::now())
					->orderBy('a.post_id')
					->orderBy('a.created_at')
					->orderBy('order_is_discharge','desc')
					->orderBy('a.created_at', 'desc')
					->paginate($this->paginateValue);

			return view('order_tasks.index', [
					'order_tasks'=>$order_tasks,
			]);
	}

	public function task(Request $request, $encounter_id, $location_code = null)
	{
			Session::set('encounter_id', $encounter_id);
			$encounter = Encounter::find($encounter_id);

			//if (empty($request->cookie('queue_location'))) {
			if (!empty(Auth::user()->authorization->location_code)) {
				$location_code = Auth::user()->authorization->location_code;
			} else {
				$location_code = $request->cookie('queue_location');
			}
			$location = Location::find($location_code);
			$queue_categories = $request->cookie('queue_categories');

			$consultation = Consultation::where('patient_id','=',$encounter->patient_id)
					->orderBy('created_at','desc')
					->first();
			Session::set('consultation_id', $consultation->consultation_id);

			$fields = ['patient_name', 'product_name', 'a.product_code', 'cancel_id', 'a.order_id', 'a.post_id', 'a.created_at','order_is_discharge',
					'i.location_name',	
					'a.store_code',
					'product_stocked',
					'product_track_batch',
					'cancel_id',
					'ward_name',
					'order_quantity_request',
					'order_quantity_supply',
					'a.created_at',
					'k.name',
					'order_completed',
					'name',
					'investigation_date',
					'product_track_batch',
					'product_stocked',
					'e.category_code',
					];

			$order_tasks = DB::table('orders as a')
					->select($fields)
					->join('consultations as b', 'b.consultation_id', '=', 'a.consultation_id')
					->join('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->join('patients as d', 'd.patient_id','=', 'c.patient_id')
					->join('products as e','e.product_code','=','a.product_code')
					->leftjoin('order_investigations as l','l.order_id','=','a.order_id')
					->leftjoin('order_cancellations as f', 'f.order_id', '=', 'a.order_id')
					->leftjoin('product_categories as g', 'g.category_code', '=', 'e.category_code')
					->leftjoin('queues as h', 'h.encounter_id', '=', 'c.encounter_id')
					->leftjoin('queue_locations as i', 'i.location_code', '=', 'h.location_code')
					->leftjoin('order_posts as j', 'j.post_id', '=', 'a.post_id')
					->leftjoin('users as k','k.id','=', 'a.user_id')
					->leftjoin('wards as m','m.ward_code','=', 'a.ward_code')
					->where('c.encounter_id','=', $encounter_id)
					->whereIn('e.category_code', $queue_categories)
					->where('e.product_local_store','=',0)
					->where('a.post_id','>',0)
					->whereNull('cancel_id')
					->orderBy('order_completed')
					->orderBy('cancel_id')
					->orderBy('a.post_id')
					->orderBy('a.created_at')
					->orderBy('order_is_discharge','desc')
					->orderBy('a.created_at', 'desc');

			if ($request->future) {
				$order_tasks = $order_tasks->where('order_is_future','=', 1);
			} else {
				$order_tasks = $order_tasks->where('order_is_future','=', 0);
			}

			$order_tasks = $order_tasks->paginate($this->paginateValue);
			
			//->where('investigation_date','<', Carbon::now())
			
				
			$ids='';
			foreach ($order_tasks as $task) {
					if ($task->order_completed==0) {
							$ids .= (string)$task->order_id.",";
					}
			}

			$store=null;
			if (count($order_tasks)>0) {
				$store = Store::find($order_tasks[0]->store_code);
			}
			
			return view('order_tasks.index', [
					'order_tasks'=>$order_tasks,
					'patient'=>$encounter->patient,
					'encounter'=>$encounter,
					'encounter_id' => $encounter_id,
					'ids'=>$ids,
					'location'=>$location,
					'product'=> new Product(),
					'stock_helper'=> new StockHelper(),
					'order_helper'=> new OrderHelper(),
					'store'=>$store,
					'consultation_id'=>$consultation->consultation_id,
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
			if (!empty(Auth::user()->authorization->location_code)) {
				$location_code = Auth::user()->authorization->location_code;
			} else {
				$location_code = $request->cookie('queue_location');
			}
			$location = Location::find($location_code);

			$store_code = $location->store_code;

			$values = explode(',',$request->ids);

			foreach ($values as $orderId) {
					$value = $request->$orderId ?: 0;
					if ($orderId>0) {
							OrderTask::where('order_id', $orderId)->update(['order_completed'=>$value]);				
							if ($value=='1') {
								//OrderTask::where('order_id', $orderId)->update(['store_code'=>$store_code]);				

								$order = Order::find($orderId);
								$order->order_quantity_supply = $request['quantity_'.$order->order_id];
								$order->completed_at = DojoUtility::dateTimeWriteFormat(DojoUtility::now());
								$order->updated_by = Auth::user()->id;
								$order->location_code = $location_code;
								$order->store_code = $store_code;
								$order->save();

								if ($order->product->product_stocked==1) {
										$stock = new Stock();
										$stock->order_id = $orderId;
										$stock->product_code = $order->product_code;
										$stock->stock_quantity = -($order->order_quantity_supply);
										$stock->store_code = $store_code;
										$stock->stock_value = -($order->product->product_average_cost*$order->order_quantity_supply);
										$stock->move_code = 'sale';
										$stock->save();

										if ($stock->product->product_track_batch==1) {
											$total_quantity = $this->statusBatch($request, $stock);
											$stock->stock_quantity = -$total_quantity;
											$stock->stock_value = $order->product->product_average_cost*$total_quantity;
											$stock->save();

											$order->order_quantity_supply = $total_quantity;
											$order->save();
										} 							
								}
							} else {
								OrderTask::where('order_id', $orderId)->update(['store_code'=>null]);				
							}
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


	public function statusBatch(Request $request, $stock) 
	{
		$stock_helper = new StockHelper();

		$batches = $stock_helper->getBatches($stock->product_code, $stock->store_code);

		$total_quantity = 0;
		foreach($batches as $batch) {
			$batch_quantity = $request[$batch->product_code.'_'.$batch->batch_number];
			$batch_quantity = abs($batch_quantity);
			if (!empty($batch_quantity)) {
					$new_batch = new StockBatch();
					$new_batch->stock_id = $stock->stock_id;
					$new_batch->store_code = $stock->store_code;
					$new_batch->product_code = $stock->product_code;
					$new_batch->batch_number = $batch->batch_number;
					$new_batch->batch_quantity = -($batch_quantity);
					$new_batch->expiry_date = $batch->expiry_date;
					$new_batch->save();
					$total_quantity += $batch_quantity;
					Log::info($new_batch);
			}
		}

		return $total_quantity;
	}
}
