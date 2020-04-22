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
use App\Inventory;
use App\ProductUom;
use App\EncounterHelper;

use App\DrugDosage as Dosage;
use App\UnitMeasure as Unit;
use App\DrugRoute as Route;
use App\DrugFrequency as Frequency;
use App\DrugPrescription;
use App\Period;
use App\OrderDrugLabel;

class OrderTaskController extends Controller
{
	public $paginateValue=99;

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
					->where('e.product_drop_charge', '<>', '1')
					->whereNull('cancel_id')
					->where('investigation_date','<', Carbon::now())
					->orderBy('a.post_id')
					->orderBy('a.created_at')
					->orderBy('order_is_discharge','desc')
					->orderBy('a.created_at', 'desc')
					->paginate($this->paginateValue);

			return view('order_tasks.index', [
					'order_tasks'=>$order_tasks,
					'encounter_id'=>$encounter_id,
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
			//$queue_categories = $request->cookie('queue_categories');
			$queue_encounters = explode(';',Auth::user()->authorization->queue_encounters);
			$queue_categories = explode(';',Auth::user()->authorization->queue_categories);

			$consultation = Consultation::where('patient_id','=',$encounter->patient_id)
					->orderBy('created_at','desc')
					->first();
			Session::set('consultation_id', $consultation->consultation_id);

			$fields = ['patient_name', 'product_name', 'a.product_code', 'cancel_id', 'a.order_id', 'a.post_id', 'a.created_at as order_date','order_is_discharge',
					'i.location_name',	
					'a.store_code',
					'product_stocked',
					'cancel_id',
					'ward_name',
					'order_quantity_request',
					'order_quantity_supply',
					'a.created_at',
					'k.name',
					'order_completed',
					'k.name',
					'n.name as updated_user',
					'investigation_date',
					'product_stocked',
					'e.category_code',
					'completed_at',
					'dispensed_by',
					'b.created_at as consultation_date',
					'order_description',
					'product_local_store',
					'a.user_id',
					'a.consultation_id',
					'o.name as dispensed_user',
					'dispensed_at',
					'stop_id',
					'stop_description',
					'p.created_at as stop_at',
					'q.name as stop_by'
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
					->leftjoin('users as n','n.id','=', 'a.completed_by')
					->leftjoin('users as o','o.id','=', 'a.dispensed_by')
					->leftjoin('order_stops as p', 'p.order_id', '=', 'a.order_id')
					->leftjoin('users as q', 'q.id', '=', 'p.user_id')
					->where('a.encounter_id','=', $encounter_id)
					->whereIn('e.category_code', $queue_categories)
					->where('a.post_id','>',0) 
					->whereNull('cancel_id') 
					->orderBy('order_is_discharge', 'desc')
					->orderBy('a.consultation_id');

			$now = date('Y-m-d');
			if ($now<'2019-10-11') {
					$order_tasks = $order_tasks->whereNull('origin_id');
			}


					/*
					->orderBy('order_completed')
					->orderBy('cancel_id')
					->orderBy('a.post_id')
					->orderBy('a.created_at')
					->orderBy('order_is_discharge','desc')
					->orderBy('a.created_at', 'desc');
					*/

					//->where('e.product_local_store','=',0)

			if ($request->future) {
				$order_tasks = $order_tasks->where('order_is_future','=', 1);
			} else {
				$order_tasks = $order_tasks->where('order_is_future','=', 0);
			}

			$count_ward = 0;
			$count_floor = 0;
			$count_discharge = 0;
			$count_completed = 0;

			$count_orders = clone $order_tasks;
			$count_ward = $count_orders->where('product_local_store',0)
								->where('order_is_discharge', 0)
								->where('order_completed', 0)
								->count();

			$count_orders = clone $order_tasks;
			$count_floor = $count_orders->where('product_local_store',1)
								->count();

			$count_orders = clone $order_tasks;
			$count_discharge = $count_orders->where('order_is_discharge', 1)
								->where('order_completed', 0)
								->count();

			$count_orders = clone $order_tasks;
			$count_completed = $count_orders->where('order_completed', 1)
								->whereNull('stop_id')
								->where('product_local_store',0)
								->count();

			$count_orders = clone $order_tasks;
			$count_stop = $count_orders->where('order_completed', 1)
								->whereNotNull('stop_id')
								->count();

			$order_tasks = $order_tasks->paginate($this->paginateValue);
			
			//->where('investigation_date','<', Carbon::now())
			
				
			$ids='';
			$dispense_ids = '';
			foreach ($order_tasks as $task) {
					if ($task->order_completed==0) {
							$ids .= (string)$task->order_id.",";
					}
					if ($task->order_completed==1 && empty($task->dispensed_by)) {
							$dispense_ids .= (string)$task->order_id.",";
					}
			}

			$store=null;
			if (count($order_tasks)>0) {
				$store = Store::find($order_tasks[0]->store_code);
			}
			
			//return view('order_tasks.index', [
			$view = 'order_tasks.index';

			if (Auth::user()->author_id==18 or Auth::user()->author_id==13) {
					$view = 'order_tasks.pharmacy';
			}
			
			return view($view, [
					'order_tasks'=>$order_tasks,
					'patient'=>$encounter->patient,
					'encounter'=>$encounter,
					'encounter_id' => $encounter_id,
					'ids'=>$ids,
					'dispense_ids'=>$dispense_ids,
					'location'=>$location,
					'stock_helper'=> new StockHelper(),
					'order_helper'=> new OrderHelper(),
					'store'=>$store,
					'consultation_id'=>$consultation->consultation_id,
					'count_ward'=>$count_ward,
					'count_stop'=>$count_stop,
					'count_floor'=>$count_floor,
					'count_discharge'=>$count_discharge,
					'count_completed'=>$count_completed,
					'show_line'=>False,
					'new_line'=>False,
					'user_id'=>0,
			]);
	}
	public function edit(Request $request, $id) 
	{
			$order_task = OrderTask::findOrFail($id);
			$frequencies = Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('','');

			return view('order_tasks.edit', [
					'order_task'=>$order_task,
					'product' => $order_task->product,
					'patient'=>$order_task->consultation->encounter->patient,
					'encounter'=>$order_task->consultation->encounter,
					'encounter_id' => $order_task->consultation->encounter_id,
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_name')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'report'=>$request->queue?true:false,
					'mar'=>$request->mar?true:false,
					'unit' => Unit::where('unit_drug',1)->orderBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'dosage' => Dosage::all()->sortBy('dosage_index')->lists('dosage_name', 'dosage_code')->prepend('',''),
					'route' => Route::all()->sortBy('route_name')->lists('route_name', 'route_code')->prepend('',''),
					'frequency' => $frequencies,
					'period' => Period::whereIn('period_code', array('day','week', 'month'))->orderBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'order_helper'=> new OrderHelper(),
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_task = OrderTask::findOrFail($id);

			$order_task->fill($request->input());

			//$order_task->order_completed = $request->order_completed ?: 0;
			$valid = $order_task->validate($request->all(), $request->_method);	
			
			

			if ($valid->passes()) {
					if (!empty($request->order_report)) {
						$order_task->reported_by = Auth::user()->id;
					}
					$order_task->save();
					if (!empty($request->dosage_code)) {
							$drug_label = OrderDrugLabel::where('order_id', $id)->first();
							$drug_label->dosage_code = $request->dosage_code;
							$drug_label->drug_dosage = $request->drug_dosage;
							$drug_label->drug_strength = $request->drug_strength;
							$drug_label->unit_code = $request->unit_code;
							$drug_label->drug_dosage = $request->drug_dosage;
							$drug_label->dosage_code = $request->dosage_code;
							$drug_label->route_code = $request->route_code;
							$drug_label->frequency_code = $request->frequency_code;
							$drug_label->drug_duration = $request->drug_duration;
							$drug_label->period_code = $request->period_code;
							$drug_label->save();
					}
					$productController = new ProductController();
					$productController->updateTotalOnHand($order_task->product_code);
					Session::flash('message', 'Record successfully updated.');
					if ($request->user()->can('module-support')) {
						if ($request->report==1) {
								return redirect('/order_tasks/'.$order_task->order_id.'/edit?queue=report');
						} else {
								//return redirect('/order_tasks/'.$order_task->order_id.'/edit');
								return redirect('/order_tasks/task/'.$order_task->encounter_id);
						}
					} else {
						if ($request->mar) {
								return redirect('/medication_record/mar/'.$order_task->encounter_id.'?admission=1');
						} else {
								return redirect('/admission_tasks');
						} 
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

	public function reopen($id)
	{
			Inventory::where('order_id', $id)->delete();
			$order = Order::find($id);
			$order->order_completed = 0;
			$order->save();
			Session::flash('message', 'Order reopened.');
			return redirect('/order_tasks/task/'.$order->encounter_id.'/'.$order->location_code);
	}

	public function status(Request $request)
	{
			$valid = null;
			if (!empty(Auth::user()->authorization->location_code)) {
				$location_code = Auth::user()->authorization->location_code;
			} else {
				$location_code = $request->cookie('queue_location');
			}
			$location = Location::find($location_code);
			$store_code = $location->store_code;

			$helper = new StockHelper();

			$order_ids = explode(',', $request->ids);

			$orders = Order::where('encounter_id', $request->encounter_id)
							->whereIn('order_id', $order_ids)
							->get();

			/*** Validation ***/
			Log::info("Validate....");
			foreach($orders as $order) {
				Log::info("Checkin === ".$order->order_id);
				$checked = $request[$order->order_id] ?:0;

				if ($checked == 1) {
						$batches = $helper->getBatches($order->product_code)?:null;
						$last_batch = "";
						if ($batches->count()>0) {
								$unit_supply = 0;
								foreach($batches as $batch) {
										Log::info('--->'.$batch->batch());
										if (!empty($batch->batch())) {
										Log::info('================='.'batch_'.$order->order_id.'_'.$batch->product_code."_".$batch->batch()->batch_id);
										Log::info('================='.$request['batch_'.$order->order_id.'_'.$batch->product_code."_".$batch->batch()->batch_id]);
										$unit_supply += $request['batch_'.$order->order_id.'_'.$batch->product_code."_".$batch->batch()->batch_id]?:0;
										$last_batch = $batch->batch()->batch_id;
										Log::info('unit supply:'.$unit_supply);
										}
								}
								if ($unit_supply == 0) {
											$valid['batch_'.$batch->product_code] = "Sum cannot be zero";
								}
						} else {
								if ($order->product->product_stocked == 1) {
										$unit_supply = $request["quantity_".$order->order_id];
										if ($unit_supply == 0) {
												$valid["quantity_".$order->order_id] = "Cannot be zero";
										}
								}
						}
				}

			}

			if ($valid) {
					return redirect('/order_tasks/task/'.$order->encounter_id.'/'.$order->location_code)
							->withErrors($valid)
							->withInput();
			}
			/*** End Validation ***/

			foreach($orders as $order) {
					$product = $order->product;
					$checked = $request[$order->order_id] ?:0;

					if ($checked == 1) {
						//$total_supply = $order->order_quantity_supply;
						$total_supply = $request['quantity_'.$order->order_id];
						if (empty($total_supply)) {
								$total_supply = 1;
						}

						$batches = $helper->getBatches($order->product_code)?:null;

						if ($batches->count()>0) {
								$total_supply = 0;
								foreach($batches as $batch) {
									if ($batch->batch()) {
										$unit_supply = $request['batch_'.$order->order_id.'_'.$batch->product_code."_".$batch->batch()->batch_id]?:0;
										if ($unit_supply>0) {
												$total_supply = $total_supply + $unit_supply;

												$inventory = new Inventory();
												$inventory->order_id = $order->order_id;
												$inventory->store_code = $store_code;
												$inventory->product_code = $order->product_code;
												$inventory->unit_code = $order->unit_code;

												$uom = null;
												if (empty($order->unit_code)) {
													$uom = ProductUom::where('product_code', $order->product_code)
															->where('uom_default_price', 1)
															->first();

												} else {
													$uom = ProductUom::where('product_code', $order->product_code)
															->where('unit_code', $inventory->unit_code)
															->first();
												}

												if (empty($uom)) {
													$uom = ProductUom::where('product_code', $order->product_code)
															->where('uom_default_price', 1)
															->first();
												}

												$inventory->uom_rate =  $uom->uom_rate;
												$inventory->unit_code = $uom->unit_code;
												$inventory->inv_unit_cost =  $uom->uom_cost?:0;
												$inventory->inv_quantity = -($unit_supply*$uom->uom_rate);
												$inventory->inv_physical_quantity = $unit_supply;
												$inventory->inv_subtotal =  -($uom->uom_cost*$inventory->inv_physical_quantity);
												$inventory->move_code = 'sale';
												$inventory->inv_batch_number = $batch->inv_batch_number;
												$inventory->inv_posted = 1;
												$inventory->save();
												Log::info("wwwwwwwwwwwwwwwwwwwwwwwwwwww");
										}
									}
								}
						} else {
							$total_supply = $request["quantity_".$order->order_id];
							if (empty($total_supply)) {
									$total_supply = 1;
							}
							if ($product->product_stocked==1) {
									$inventory = new Inventory();
									$inventory->order_id = $order->order_id;
									$inventory->store_code = $store_code;
									$inventory->product_code = $order->product_code;
									$inventory->unit_code = $order->unit_code;

									$uom = ProductUom::where('product_code', $order->product_code)
											->where('unit_code', $inventory->unit_code)
											->first();

									$inventory->uom_rate =  $uom->uom_rate;
									$inventory->inv_unit_cost =  $uom->uom_cost?:0;
									$inventory->inv_quantity = -($total_supply*$uom->uom_rate);
									$inventory->inv_physical_quantity = $total_supply;
									$inventory->inv_subtotal =  $uom->uom_cost*$inventory->inv_physical_quantity;
									$inventory->move_code = 'sale';
									$inventory->inv_posted = 1;
									$inventory->save();
									Log::info("qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq");
							}
						}

						/** Completed order **/
						/*
						$uom = ProductUom::where('product_code', $order->product_code)
									->where('unit_code', $inventory->unit_code)
									->first();
						 */

						$order = Order::find($order->order_id);
						$order->order_quantity_request = $total_supply;
						$order->order_quantity_supply = $total_supply;

						//$encounter_code = $order->consultation->encounter->encounter_code;
						//if ($encounter_code == 'inpatient' || $encounter_code = 'daycare') {
						//} else {
						//	$order->order_quantity_supply = $total_supply;
						//}

						$order->completed_at = DojoUtility::dateTimeWriteFormat(DojoUtility::now());
						$order->completed_by = Auth::user()->id;
						$order->location_code = $location_code;
						$order->store_code = $store_code;
						$order->order_completed = 1;
						if ($order->order_is_future==1) {
							$order->order_is_future = 2;
						}
						$order->save();
						
					} else {
						/*
						if ($order->order_completed == 1) {
							Inventory::where('order_id', $order->order_id)->delete();
							$order->order_completed = 0;
							$order->completed_at = null;
							$order->order_quantity_supply = null;
							$order->save();
						} else {
							$order->order_quantity_request = $request['quantity_'.$order->order_id];
							$order->save();
						}
						 */
					}

					if ($product->product_stocked == 1) {
							$helper->updateStockOnHand($order->product_code);
					}
			}

			$dispense_ids = explode(',', $request->dispense_ids);

			$orders = Order::where('encounter_id', $request->encounter_id)
							->whereIn('order_id', $dispense_ids)
							->get();

			foreach($orders as $order) {
					$dispensed = $request['dispense_'.$order->order_id] ?:0;

					if ($dispensed == 1) {
							$order->dispensed_by = Auth::user()->id;
							$order->dispensed_at = DojoUtility::dateTimeWriteFormat(DojoUtility::now());
							$order->save();
					}
			}


			Session::flash('message', 'Record successfully updated.');
			return redirect('order_queues');
	}

	public function destroyInventory($id)
	{	
			Inventory::find($id)->delete();
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
