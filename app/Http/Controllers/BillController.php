<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Bill;
use App\Encounter;
use Log;
use DB;
use Session;
use Auth;
use App\DojoUtility;
use App\User;
use App\PatientType;
use App\Sponsor;
use App\BillAging;
use App\BillHelper;
use App\EncounterType;
use App\Order;
use App\StockHelper;
use App\Inventory;
use App\ProductUom;

class BillController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bills = Bill::orderBy('encounter_id', 'desc')
					->paginate($this->paginateValue);
			return view('bills.index', [
					'bills'=>$bills
			]);
	}

	public function create()
	{
			$bill = new Bill();
			return view('bills.create', [
					'bill' => $bill,
					]);
	}

	public function store(Request $request) 
	{
			$bill = new Bill();
			$valid = $bill->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bill = new Bill($request->all());
					$bill->user_id = Auth::user()->id;
					$bill->id = $request->id;
					if (empty($bill->bill_total)) {
							$bill->bill_total = 0;
					}


					$encounter = Encounter::find($bill->encounter_id);
					if (!empty($encounter->sponsor_code)) {
							$bill->bill_non_claimable = (int)$request->bill_non_claimable;
					}

					try {
							$bill->save();
					} catch (\Exception $e) {
							\Log::info($e->getMessage());
					} finally {
							$encounter = Encounter::find($bill->encounter_id);
							$bill_helper = new BillHelper();
							$encounter->bill_status = $bill_helper->billStatus($encounter->encounter_id);
							$encounter->save();
							$this->addDropchargeSales($bill->encounter_id);	
							return view('bills.post', [
									'patient'=>$encounter->patient,
									'encounter'=>$encounter,
							]);
					}
			} else {
					return redirect('/bills/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function updateInvDatetime() {
			ini_set('max_execution_time', 500);
			set_time_limit(500);

			$invs = Inventory::select('inv_id', 'b.created_at')
					->leftjoin('orders as b', 'b.order_id', '=', 'inventories.order_id')
					//->where('inventories.product_code', '201744')
					->where('move_code', 'sale')
					->where('inv_drop_charge', 1)
					//->where('encounter_id', 7047)
					->get();

			foreach($invs as $inv) {

				Inventory::where('inv_id', $inv->inv_id)->update(['inv_datetime'=>$inv->created_at]);
				Log::info($inv);
			}

			return "Ok...";
	}
	
	public function fixDropChargeSales() {

			// Steps
			// 1 - test2
			// 2 - test3
			//
			
			ini_set('max_execution_time', 500);
			set_time_limit(500);

			$ids = Order::distinct("orders.encounter_id")
						->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
						->leftjoin('inventories as c', 'c.order_id', '=', 'orders.order_id')
						->where('product_drop_charge', '1')
						->where('product_stocked', '1')
						->where('b.category_code', 'drugs')
						->whereNull('c.order_id')
						//->where('orders.product_code', '201744')
						->where('encounter_id', '7086')
						->orderBy('encounter_id')
						->pluck("encounter_id");

			foreach($ids as $id) {
				Log::info("-------->");
				Log::info($id);
				Log::info("-------->");
				$this->addDropchargeSales($id);
			}

			return "Ok";
		
	}

	public function addDropchargeSales($id) {
			$ids = Order::select("orders.order_id")
						->where('encounter_id', $id)
						->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
						->leftjoin('inventories as c', 'c.order_id', '=', 'orders.order_id')
						->where('product_drop_charge', '1')
						->where('product_stocked', '1')
						->where('b.category_code', 'drugs')
						->whereNull('c.order_id')
						->pluck("orders.order_id");

			$orders = Order::whereIn('order_id', $ids)->get();

			foreach ($orders as $order) {

					Log::info("-------->");
					Log::info("order id: ".$order->product->product_name);
					$helper = new StockHelper();
					$batches = $helper->getBatches($order->product_code, $order->order_id)?:null;

					if ($batches->count()>0) {
							$total_supply = 0;
							foreach($batches as $batch) {
									if ($batch->batch()) {
											$unit_supply = $order->order_quantity_supply;
											if ($unit_supply>0) {
													$total_supply = $total_supply + $unit_supply;

													$uom = $this->getUOM($order);
													if (empty($uom)) {
															$uom_cost = 0;
															$uom_rate = 1;
													} else {
															$uom_cost = $uom->uom_cost?:0;
															$uom_rate = $uom->uom_rate?:1;
													}


													$inventory = new Inventory();
													$inventory->order_id = $order->order_id;
													$inventory->store_code = $order->store_code;
													$inventory->product_code = $order->product_code;
													$inventory->unit_code = $order->unit_code;
													$inventory->uom_rate =  $uom_rate?:1;
													$inventory->unit_code = $uom?$uom->unit_code:'unit';
													$inventory->inv_unit_cost =  $uom_cost?:0;
													$inventory->inv_quantity = -($unit_supply*$uom_rate);
													$inventory->inv_physical_quantity = $unit_supply;
													$inventory->inv_subtotal =  -($uom_cost*$inventory->inv_physical_quantity);
													$inventory->move_code = 'sale';
													$inventory->inv_batch_number = $batch->inv_batch_number;
													$inventory->inv_posted = 1;
													$inventory->save();
													//Log::info($inventory);
											}
									}
							}
					} else {
							$total_supply = $order->order_quantity_supply?:1;

							if ($order->product->product_stocked==1) {

									$uom = $this->getUOM($order);
									if (empty($uom)) {
											$uom_cost = 0;
											$uom_rate = 1;
									} else {
											$uom_cost = $uom->uom_cost?:0;
											$uom_rate = $uom->uom_rate?:1;
									}

									$inventory = new Inventory();
									$inventory->order_id = $order->order_id;
									$inventory->store_code = $order->store_code;
									$inventory->product_code = $order->product_code;
									$inventory->unit_code = $order->unit_code;
									$inventory->uom_rate =  $uom_rate;
									$inventory->inv_unit_cost =  $uom_cost;
									$inventory->inv_quantity = -($total_supply*$uom_rate);
									$inventory->inv_physical_quantity = $total_supply;
									$inventory->inv_subtotal =  $uom_cost*$inventory->inv_physical_quantity;
									$inventory->move_code = 'sale';
									$inventory->inv_posted = 1;
									$inventory->save();
									//Log::info($inventory);
							}
					}
			}
	}

	public function getUOM($order) {
			$uom = null;
			$uom = ProductUom::where('product_code', $order->product_code)
					->where('uom_default_price', 1)
					->first();

			if (empty($uom)) {
					$uom = productuom::where('product_code', $order->product_code)
							->where('unit_code', $order->unit_code)
							->first();
			}

			return $uom;
	}

	public function edit($id) 
	{
			$bill = Bill::findOrFail($id);
			return view('bills.edit', [
					'bill'=>$bill,
				
					]);
	}

	public function json($id) 
	{
			$bill = Bill::find($id);
			if ($bill) {
					return $bill;
			} else {
					return "Bill not posted";
			}
	}

	public function update(Request $request, $id) 
	{
			$bill = Bill::findOrFail($id);
			$bill->fill($request->input());


			$valid = $bill->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bill->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bills/id/'.$id);
			} else {
					return view('bills.edit', [
							'bill'=>$bill,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bill = Bill::findOrFail($id);
		return view('bills.destroy', [
			'bill'=>$bill
			]);

	}
	public function destroy($id)
	{	
			Bill::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bills');
	}
	
	public function search(Request $request)
	{
			$bills = Bill::where('id','like','%'.$request->search.'%')
					->orWhere('encounter_id', 'like','%'.$request->search.'%')
					->orderBy('bill_grand_total')
					->paginate($this->paginateValue);

			return view('bills.index', [
					'bills'=>$bills,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bills = DB::table('bills')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('bills.index', [
					'bills'=>$bills
			]);
	}

	public function enquiry(Request $request)
	{

			$subquery = "select encounter_id, sum(payment_amount) as total_paid from payments group by encounter_id";
			$bills = Bill::select(DB::raw('bills.id,patient_name, patient_mrn, encounter_code, bills.encounter_id, d.created_at as discharge_date,sponsor_name, bill_grand_total, bill_payment_total, bill_deposit_total, bills.created_at as bill_date,
					total_paid, format(bill_grand_total-IFNULL(bill_deposit_total,0)-IFNULL(total_paid,0),2) as bill_outstanding, name, bill_non_claimable'))
					->leftJoin('encounters as b', 'b.encounter_id', '=', 'bills.encounter_id')
					->leftJoin('patients as c', 'c.patient_id', '=',  'b.patient_id')
					->leftJoin('discharges as d', 'd.encounter_id', '=', 'b.encounter_id')
					->leftJoin('users as e', 'e.id', '=', 'bills.user_id')
					->leftJoin(DB::raw('('.$subquery.') f'), function($join) {
							$join->on('bills.encounter_id','=', 'f.encounter_id');
					})
					->leftJoin('sponsors as g', 'g.sponsor_code', '=', 'b.sponsor_code');


			if (!empty($request->search)) {
					$bills = $bills->where(function ($query) use ($request) {
							$query->where('patient_mrn','like','%'.$request->search.'%')
								->orWhere('patient_name', 'like','%'.$request->search.'%')
								->orWhere('bills.encounter_id', 'like','%'.$request->search.'%');
					});
			}

			if (!empty($request->user_id)) {
					$bills = $bills->where('bills.user_id','=', $request->user_id);
			}

			if (!empty($request->sponsor_code)) {
					$bills = $bills->where('b.sponsor_code','=', $request->sponsor_code);
			}

			if (!empty($request->encounter_code)) {
					$bills = $bills->where('b.encounter_code','=', $request->encounter_code);
			}

			if (!empty($request->type_code)) {
					$bills = $bills->where('b.type_code','=', $request->type_code);
			}

			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = null;
			$time_start = '00:00';
			$time_end = '23:59';
			if (!empty($request->shift_code)) {
					switch ($request->shift_code) {
							case 'shift_1':
									$time_start = '7:00';
									$time_end = '14:00';
									$date_end = null;
									break;
							case 'shift_2':
									$time_start = '14:00';
									$time_end = '21:00';
									$date_end = null;
									break;
							case 'shift_3':
									$date_end = $request->date_start;
									$date_end = DojoUtility::addDays($date_end, 1);
									$date_end = DojoUtility::dateYMDFormat($date_end);
									$time_start = '21:00';
									$time_end = '7:00';
									break;
					}
			}
			
			if (!empty($date_start) && empty($date_end)) {
				$bills = $bills->whereBetween('bills.created_at', array($date_start.' '.$time_start, $date_start.' '.$time_end));
			}

			if (empty($date_start) && !empty($date_end)) {
				//$bills = $bills->whereBetween('bills.created_at', array($date_start.' '.$time_start, $date_end.' '.$time_end));
				//$bills = $bills->where('bills.created_at', '=', $date_end.' '.$time_end);
			}

			if (!empty($date_start) && !empty($date_end)) {
				$bills = $bills->whereBetween('bills.created_at', array($date_start.' '.$time_start, $date_end.' '.$time_end));
			} 

			if ($request->export_report) {
				DojoUtility::export_report($bills->get());
			}

			$bills = $bills->orderBy('id');
			$bills = $bills->paginate($this->paginateValue);

			$users = User::orderby('name')
							->where('author_id', 6)
							->lists('name','id')
							->prepend('','');

			return view('bills.enquiry', [
					'bills'=>$bills,
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'search'=>$request->search,
					'users'=>$users,
					'user_id'=>$request->user_id,
					'patient_types' => PatientType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'type_code' => $request->type_code,
					'sponsor' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
					'sponsor_code'=>$request->sponsor_code,
					'shift_code'=>$request->shift_code,
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'encounter_code'=>$request->encounter_code,
			]);
	}

	public function aging()
	{
			$sql = "
				select a.encounter_id, sponsor_code, (bill_grand_total - IFNULL(total_paid,0)) as age_amount, DATEDIFF(now(),a.created_at) as age_days
				from bills as a
				left join (
				select sum(payment_amount) as total_paid, encounter_id from payments
				group by encounter_id
				) as b on (a.encounter_id = b.encounter_id)
				left join encounters as c on (c.encounter_id = a.encounter_id)
			";

			$results = DB::select($sql);

	
			$today = DojoUtility::today();
			BillAging::truncate();

			if (!empty($results)) {
				foreach($results as $result) {
					$age = new BillAging();
					$age->encounter_id = $result->encounter_id;
					$age->sponsor_code = $result->sponsor_code;
					$age->age_amount = $result->age_amount?:0;
					$age->age_days = $result->age_days;
					if ($age->age_days<0) $age->age_days=0;

					if ($age->age_days>=0 & $age->age_days<=30) $age->age_group = 1;
					if ($age->age_days>=31 & $age->age_days<=60) $age->age_group = 2;
					if ($age->age_days>=61 & $age->age_days<=90) $age->age_group = 3;
					if ($age->age_days>=91 & $age->age_days<=120) $age->age_group = 4;
					if ($age->age_days>=121) $age->age_group = 5;
					$age->save();
				}
			} 

			return redirect('/bill_aging/enquiry');

	}

	public function billEdit($id) 
	{
			$encounter = Encounter::findOrFail($id);
			return view('bills.bill_edit', [
					'encounter'	=> $encounter,
					'patient'	=> $encounter->patient,
					'patient_type' => PatientType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'sponsor' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
			]);
	}

	public function billUpdate(Request $request, $id)
	{
			$encounter = Encounter::findOrFail($id);
			$sponsor = $encounter->sponsor_code;
			$encounter->fill($request->input());
			$valid = $encounter->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$resetBill = false;
					if ($encounter->type_code == 'public' && !empty($sponsor)) {
							$resetBill = true;
					}

					if ($encounter->type_code == 'sponsored' && empty($sponsor)) {
							$resetBill = true;
					}

					Log::info($resetBill?"True":"False");
					Log::info($encounter->type_code);

					if ($encounter->type_code=='public') {
						$encounter->sponsor_code = null;
						$encounter->sponsor_id=null;
					}
					$encounter->save();



					if ($resetBill) {
							DB::table('bill_items')
								->where('encounter_id','=',$encounter->encounter_id)
								->delete();

							DB::table('bills')
								->where('encounter_id','=',$encounter->encounter_id)
								->delete();

							DB::table('payments')
								->where('encounter_id','=',$encounter->encounter_id)
								->delete();
					}

					Session::flash('message', 'Record successfully updated.');
					return redirect('/bill_items/'.$encounter->encounter_id);
			} else {
					//return $valid->messages();
					return redirect('/bill/bill_edit/'.$id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function sponsorOutstanding(Request $request) {
 
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$subquery = "
				select format(sum(payment_amount),2) as total_payment, c.sponsor_code from payments as a
				left join bills as b on (a.bill_id = b.id)
				left join encounters as c on (c.encounter_id = b.encounter_id)
				where c.sponsor_code is not null
				group by c.sponsor_code
			";

			$rows = Bill::groupBy('b.sponsor_code')
					->select(DB::raw('format(sum(bill_grand_total),2) as bill_grand_total, sponsor_name, total_payment, format((total_payment-sum(bill_grand_total)),2) as outstanding'))
					->leftJoin('encounters as b', 'b.encounter_id', '=', 'bills.encounter_id')
					->leftJoin('sponsors as c', 'c.sponsor_code', '=', 'b.sponsor_code')
					->leftJoin('discharges', 'discharges.encounter_id', '=', 'b.encounter_id')
					->leftJoin(DB::raw('('.$subquery.') payments'), function($join) {
							$join->on('b.sponsor_code','=', 'payments.sponsor_code');
					})
					->where('bill_non_claimable', '=', 0)
					->orderBy('outstanding')
					->having('outstanding','<',0);


			if (!empty($date_start) && empty($request->date_end)) {
				$rows = $rows->where('discharges.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$rows = $rows->where('discharges.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$rows = $rows->whereBetween('discharges.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			//$rows = $rows->orderBy('visits');

			if ($request->export_report) {
				DojoUtility::export_report($rows->get());
			}

			$rows = $rows->get();
			$columns = ['sponsor_name'=>'Sponsor', 
					'bill_grand_total'=>'Grand Total',
					'total_payment'=>'Total Payment',
					'outstanding'=>'Outstanding'
			];

			$keys = array_keys($columns);

			return view('bills.sponsor_outstanding', [
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'columns'=>$columns,
					'rows'=>$rows,
					'keys'=>$keys
			]);
	}

	public function report(Request $request) {
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$reports = ['bill_report'=>'Bill Report',
						'panel_report'=>'Panel Report',
						'consultant_summary'=>'Consultant Report',
						'shift_report'=>'Shift Report',
						];

			return view('bills.bill_report', [
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'patient_types' => PatientType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'type_code' => $request->type_code,
					'encounter_code'=>$request->encounter_code,
					'sponsors' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
					'sponsor_code' => $request->sponsor_code,
					'reports'=>$reports,
			]);
	}
}
