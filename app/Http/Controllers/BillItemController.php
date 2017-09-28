<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BillItem;
use App\Bill;
use Log;
use DB;
use Session;
use App\Product;
use App\QueueLocation as Location;
use App\Encounter;
use App\DojoUtility;
use App\Order;
use App\OrderMultiple;
use Carbon\Carbon;
use App\FormValue;
use App\Form;
use App\ProductPriceTier;
use App\ProductCategory;

class BillItemController extends Controller
{
	public $paginateValue=1000;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function generate($id) 
	{

			$bill_existing = DB::table('bill_items')
								->where('encounter_id','=',$id);
								
			$bill_existing->delete();
			return redirect('/bill_items/'.$id);
	}

	public function bedBills($id) {
			$beds = DB::table('bed_movements as a')
						->selectRaw('*, datediff(now(),move_date) as los')
						->leftjoin('products as b', 'move_to','=', 'product_code')
						->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
						->where('encounter_id','=',$id);

			Log::info($beds->toSql());

			$beds = $beds->get();
			foreach ($beds as $bed) {
					$bed_los = $bed->los;
					if ($bed_los<=0) $bed_los=1;
					$item = new BillItem();
					$item->encounter_id = $id;
					$item->product_code = $bed->product_code;
					$item->tax_code = $bed->tax_code;
					$item->tax_rate = $bed->tax_rate;
					$item->bill_quantity = $bed_los;
					$item->bill_unit_price = $bed->product_sale_price;
					$item->bill_total_pregst = $item->bill_unit_price*$item->bill_quantity;
					$item->bill_total = $item->bill_unit_price*$item->bill_quantity;

					if ($bed->tax_rate) {
						$item->bill_total = $item->bill_total*(($bed->tax_rate/100)+1);
					}

					try {
							$item->save();
					} catch (\Exception $e) {
							\Log::info($e->getMessage());
					}
			}
	}

	public function getPriceTier($encounter_id, $product)
	{
			$encounter = Encounter::find($encounter_id);
			$cost = $product->product_cost;
			if ($cost==0) $cost=$product->product_sale_price;

			$value=0;
			$tiers = ProductPriceTier::where('charge_code','=', $product->charge_code)->get();

			if ($tiers->count()>0) {
					foreach ($tiers as $tier) {
						if ($cost<=$tier->tier_max && empty($tier->tier_min)) {
							break;
						} 
						if ($cost>$tier->tier_min && $cost<=$tier->tier_max) {
							break;
						} 
						if ($cost>$tier->tier_min && empty($tier->tier_max)) {
							break;
						}
					}
			} else {
					$tier = $tiers[0];
			}

			if ($encounter->encounter_code=='inpatient') {
					if (!empty($tier->tier_inpatient_multiplier)) {
							$value = $tier->tier_inpatient_multiplier*$cost;
					} else {
							$value = $tier->tier_inpatient;
					}

					if (!empty($tier->tier_inpatient_limit)) {
						if ($value>$tier->tier_inpatient_limit) $value = $tier->tier_inpatient_limit;
					}
			}

			if ($encounter->encounter_code=='outpatient') {
					if (!empty($tier->tier_outpatient_multiplier)) {
							$value = $tier->tier_outpatient_multiplier*$cost;
					} else {
							$value = $tier->tier_outpatient;
					}

					if (!empty($tier->tier_outpatient_limit)) {
						if ($value>$tier->tier_outpatient_limit) $value = $tier->tier_outpatient_limit;
					}
			}

			return $value;

	}
	public function compileBill($encounter_id) 
	{

			$encounter = Encounter::find($encounter_id);
			$patient_id = $encounter->patient_id;

			$sql = sprintf("
				select a.product_code, sum(order_quantity_supply) as order_quantity_supply, c.tax_rate, c.tax_code, product_sale_price, profit_multiplier
				from orders as a
				left join products as b on b.product_code = a.product_code 
				left join tax_codes as c on c.tax_code = b.tax_code 
				left join bill_items as f on (f.encounter_id=a.encounter_id and f.product_code = a.product_code)
				left join encounters as g on (g.encounter_id=a.encounter_id)
				left join patients as h on (h.patient_id = g.patient_id)
				left join ref_encounter_types as i on (i.encounter_code = g.encounter_code)
				where order_completed = 1 
				and h.patient_id = %d
				and bill_id is null 
				and order_multiple=0
				group by product_code
			", $patient_id);

			$orders = DB::select($sql);

			foreach ($orders as $order) {
					$item = new BillItem();
					$item->encounter_id = $encounter_id;
					$item->product_code = $order->product_code;
					$item->tax_code = $order->tax_code;
					$item->tax_rate = $order->tax_rate;
					$item->bill_quantity = $order->order_quantity_supply;
					$item->bill_unit_multiplier = $order->profit_multiplier;

					$product = Product::find($item->product_code);
					if (!empty($product->charge_code)) {
							$sale_price = $this->getPriceTier($encounter_id, $product);
							$item->bill_unit_price = $sale_price;
					} else {
							$item->bill_unit_price = $order->product_sale_price;
							//$item->bill_unit_price = $order->product_sale_price*(1+($order->profit_multiplier/100));
					}

					$item->bill_total = $order->order_quantity_supply*$item->bill_unit_price;
					$item->bill_total_pregst = $order->order_quantity_supply*$item->bill_unit_price;
					if ($order->tax_rate) {
						$item->bill_total = $item->bill_total*(($order->tax_rate/100)+1);
					}
					try {
							$item->save();
					} catch (\Exception $e) {
							\Log::info($e->getMessage());
					}
			}

			$this->bedBills($encounter_id);
			$this->multipleOrders($encounter_id);
			$this->forms($encounter_id);
			$this->outstandingCharges($encounter_id);
	}

	public function multipleOrders($id) 
	{
			$sql = sprintf("
				select count(a.order_id) as order_quantity_supply, b.product_code, d.tax_code, tax_rate, c.product_sale_price, d.tax_code, d.tax_rate, profit_multiplier
				from order_multiples a
				left join orders b on (b.order_id = a.order_id)
				left join products c on (c.product_code = b.product_code)
				left join tax_codes d on (d.tax_code = c.tax_code)
				left join encounters as g on (g.encounter_id=b.encounter_id)
				left join patients as h on (h.patient_id = g.patient_id)
				left join ref_encounter_types as i on (i.encounter_code = g.encounter_code)
				where b.encounter_id=%d
				and a.order_completed=1
				group by a.order_id
			", $id);
			
			$orders = DB::select($sql);

			foreach ($orders as $order) {
				$item = new BillItem();
				$item->encounter_id = $id;
				$item->product_code = $order->product_code;
				$item->tax_code = $order->tax_code;
				$item->tax_rate = $order->tax_rate;
				$item->bill_quantity = $order->order_quantity_supply;
				$item->bill_unit_multiplier = $order->profit_multiplier;
				$item->bill_unit_price = $order->product_sale_price*(1+($order->profit_multiplier/100));
				$item->bill_total = $order->order_quantity_supply*$item->bill_unit_price;
				$item->bill_total_pregst = $order->order_quantity_supply*$item->bill_unit_price;
				if ($order->tax_rate) {
						$item->bill_total = $item->bill_total*(($order->tax_rate/100)+1);
				}

				try {
					$item->save();
				} catch (\Exception $e) {
					\Log::info($e->getMessage());
				}
			}
	}

	public function forms($encounter_id)
	{
			$sql = sprintf("
				select count(*) as order_quantity_supply, c.product_code, d.tax_code, tax_rate, c.product_sale_price, d.tax_code, d.tax_rate, profit_multiplier
				from form_values a
				left join forms b on (b.form_code = a.form_code)
				left join products c on (c.form_code = b.form_code)
				left join tax_codes d on (d.tax_code = c.tax_code)
				left join encounters as g on (g.encounter_id=a.encounter_id)
				left join patients as h on (h.patient_id = g.patient_id)
				left join ref_encounter_types as i on (i.encounter_code = g.encounter_code)
				where a.encounter_id=%d
				group by a.form_code, c.product_code, profit_multiplier
			", $encounter_id);
			
			$orders = DB::select($sql);

			foreach ($orders as $order) {
				$item = new BillItem();
				$item->encounter_id = $encounter_id;
				$item->product_code = $order->product_code;
				$item->tax_code = $order->tax_code;
				$item->tax_rate = $order->tax_rate;
				$item->bill_quantity = $order->order_quantity_supply;
				$item->bill_unit_multiplier = $order->profit_multiplier;
				$item->bill_unit_price = $order->product_sale_price*(1+($order->profit_multiplier/100));
				$item->bill_total = $order->order_quantity_supply*$item->bill_unit_price;
				$item->bill_total_pregst = $order->order_quantity_supply*$item->bill_unit_price;
				if ($order->tax_rate) {
						$item->bill_total = $item->bill_total*(($order->tax_rate/100)+1);
				}

				try {
					$item->save();
				} catch (\Exception $e) {
					\Log::info($e->getMessage());
				}
			}
	}

	public function outstandingCharges($encounter_id)
	{
			$encounter = Encounter::find($encounter_id);
			Log::info($encounter->patient->outstandingBill());

			$outstanding = abs($encounter->patient->outstandingBill());
			if (abs($outstanding)>0) {
				$item = new BillItem();
				$item->encounter_id = $encounter_id;
				$item->product_code = 'others';
				$item->bill_quantity = 1;
				$item->bill_unit_price = $outstanding; 
				$item->bill_total = $outstanding;
				$item->bill_total_pregst = $outstanding;
				$item->save();
				Log::info($item);
			}
	}

	public function index($id)
	{
			$encounter = Encounter::find($id);

			$bills = DB::table('bill_items as a')
					->select('bill_id','a.encounter_id','a.product_code','product_name','a.tax_code','a.tax_rate','bill_discount','bill_quantity','bill_unit_price','bill_total','bill_total_pregst','bill_exempted')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->where('a.encounter_id','=', $id)
					->orderBy('product_name');

			$bills = $bills->paginate($this->paginateValue);


			if ($bills->total()==0) {
				$bills=$this->compileBill($id);
			$bills = DB::table('bill_items as a')
					->select('bill_id','a.encounter_id','a.product_code','product_name','a.tax_code','a.tax_rate','bill_discount','bill_quantity','bill_unit_price','bill_total','bill_total_pregst','bill_exempted')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->where('a.encounter_id','=', $id)
					->orderBy('product_name')
					->paginate($this->paginateValue);
			}

			$pending = DB::table('bill_items as a')
					->select('bill_id')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->where('a.encounter_id','=', $id)
					->count('bill_id');

			$bill_grand_total = DB::table('bill_items')
					->where('encounter_id','=', $id)
					->sum('bill_total');

			if (empty($bill_grand_total)) {
					$bill_grand_total=0;
			} else {
					$bill_grand_total = DojoUtility::roundUp($bill_grand_total);
			}


			$gst_total = DB::table('bill_items as a')
					->selectRaw('sum(bill_total_pregst) as gst_amount, format(sum(bill_total_pregst*(tax_rate/100)),2) as gst_sum, tax_code')
					->where('encounter_id','=', $id)
					->whereNotNull('tax_code')
					->groupBy('tax_code')
					->get();

			$payments = DB::table('payments as a')
					->leftJoin('payment_methods as b', 'b.payment_code','=','a.payment_code')
					->where('encounter_id','=', $id)
					->paginate($this->paginateValue);
			
			$payment_total = DB::table('payments')
					->where('encounter_id','=', $id)
					->sum('payment_amount');

			if (empty($payment_total)) {
					$payment_total=0;
			}

			$deposit_total = DB::table('deposits')
					->where('encounter_id','=', $id)
					->sum('deposit_amount');
			
			if (empty($deposit_total)) {
					$deposit_total=0;
			}

			$bill_change=0;
			$bill_outstanding=0;
			
			$balance = $payment_total+$deposit_total-$bill_grand_total;

			if ($balance<0) {
					$bill_outstanding = $balance;
			}

			if ($balance>0) {
					$bill_change = $balance;
			}

			$billPost = Bill::where('encounter_id','=',$id)->get();
			
			$billPosted=False;
			if (count($billPost)>0) $billPosted=True;

			$incomplete_orders = Order::where('encounter_id','=',$id)
									->where('order_completed','=',0)
									->leftjoin('order_cancellations as b','orders.order_id','=', 'b.order_id')
									->whereNull('cancel_id')
									->count();
									//->where('order_multiple','=',0)

			/**
			$incomplete_orders = $incomplete_orders + OrderMultiple::where('encounter_id','=',$id)
									->leftJoin('orders as b', 'b.order_id', '=', 'order_multiples.order_id')
									->leftjoin('order_cancellations as c','c.order_id','=', 'b.order_id')
									->where('order_multiples.order_completed','=',0)
									->whereNull('cancel_id')
									->count();
			**/

			return view('bill_items.index', [
					'bills'=>$bills,
					'billPosted'=>$billPosted,
					'bill_grand_total'=>$bill_grand_total,
					'patient' => $encounter->patient,
					'payments' => $payments,
					'payment_total' => $payment_total,
					'gst_total' => $gst_total,
					'encounter' => $encounter,
					'encounter_id' => $id,
					'deposit_total' => $deposit_total,
					'bill_change' => $bill_change,
					'bill_outstanding' => $bill_outstanding,
					'pending' => $pending,
					'incomplete_orders'=>$incomplete_orders,
			]);
	}

	public function create()
	{
			$bill = new BillItem();
			return view('bill_items.create', [
					'bill' => $bill,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$bill = new BillItem();
			$valid = $bill->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bill = new Bill($request->all());
					$bill->order_id = $request->order_id;
					$bill->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bill_items/id/'.$bill->order_id);
			} else {
					return redirect('/bill_items/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bill = BillItem::findOrFail($id);
			return view('bill_items.edit', [
					'bill'=>$bill,
					'product' => Product::find($bill->product_code),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'patient' => $bill->encounter->patient,
					'encounter' => $bill->encounter,
					]);
	}

	public function update(Request $request, $id) 
	{
			$bill = BillItem::findOrFail($id);
			$bill->fill($request->input());

			$product = Product::find($bill->product_code);

			switch ($product->category_code) {
					case "drugs":
						if ($product->product_unit_charge==1) {
							$bill->bill_total = $bill->bill_quantity*$bill->bill_unit_price;
						} else {
							$bill->bill_total = $bill->bill_unit_price;
						}
						break;
					default:
						$bill->bill_total = $bill->bill_quantity*$bill->bill_unit_price;
			}

			if ($bill->product->tax_code) {
					$bill->bill_total = $bill->bill_total * (1+($bill->product->tax->tax_rate/100));
			}

			if ($bill->bill_discount>0) {
					$bill->bill_total = $bill->bill_total * (1-($bill->bill_discount/100));
			}


			$valid = $bill->validate($request->all(), $request->_method);

			if ($valid->passes()) {
				$bill->bill_exempted = $request->bill_exempted ?: 0;
				if ($bill->bill_exempted) $bill->bill_total=0;
				$bill->save();
				Session::flash('message', 'Record successfully updated.');
				return redirect('/bill_items/'.$bill->encounter_id);
			} else {
				return view('bill_items.edit', [
						'bill'=>$bill,
						'patient'=>$bill->encounter->patient,
						'product' => Product::find($bill->product_code),
						'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
				])
				->withErrors($valid);			
			}

	}
	
	public function delete($id)
	{
		$bill = BillItem::find($id);
		return view('bill_items.destroy', [
			'bill'=>$bill,
			'patient'=>$bill->encounter->patient,
			]);

	}

	public function destroy($id)
	{	
		$bill = BillItem::find($id);
			BillItem::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bill_items/'.$bill->encounter_id);
	}
	
	public function reload($id)
	{
		$bill = BillItem::where('encounter_id','=',$id)->first();
		return view('bill_items.reload', [
			'bill'=>$bill,
			'patient'=>$bill->encounter->patient,
			'encounter'=>$bill->encounter,
			]);

	}

	public function search(Request $request)
	{
			$bills = DB::table('orders')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('order_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('bill_items.index', [
					'bills'=>$bills,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bills = DB::table('orders')
					->where('order_id','=',$id)
					->paginate($this->paginateValue);

			return view('bill_items.index', [
					'bills'=>$bills
			]);
	}

	public function json($id)
	{
			$bill_items = BillItem::where('encounter_id','=', $id)->get();
			return $bill_items;
	}

	public function enquiry(Request $request)
	{
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$charges = Order::select(DB::raw('orders.encounter_id, patient_name, patient_mrn, product_name, d.product_code, (order_quantity_supply*order_unit_price) as total,
					orders.created_at as order_date'))
					->leftJoin('encounters as b', 'b.encounter_id', '=', 'orders.encounter_id')
					->leftJoin('patients as c', 'c.patient_id', '=',  'b.patient_id')
					->leftJoin('products as d', 'd.product_code', '=', 'orders.product_code')
					->where('order_completed',1)
					->orderBy('b.encounter_id', 'desc')
					->orderBy('orders.order_id');

			if (!empty($request->search)) {
					if (is_numeric($request->search)) {
							$charges = $charges->where('orders.encounter_id','=', $request->search);
					} else {
							$charges = $charges->where(function ($query) use ($request) {
									$query->where('patient_mrn','like','%'.$request->search.'%')
										->orWhere('patient_name', 'like','%'.$request->search.'%');
							});
					}
			}

			if (!empty($request->category_code)) {
					$charges = $charges->where('d.category_code','=', $request->category_code);
			}

			if (!empty($date_start) && empty($request->date_end)) {
				$charges = $charges->where('orders.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$charges = $charges->where('orders.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$charges = $charges->whereBetween('orders.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if ($request->export_report) {
				DojoUtility::export_report($charges->get());
			}

			$charges = $charges->paginate($this->paginateValue);

			$categories = ProductCategory::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('','');

			return view('bill_items.enquiry', [
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'charges'=>$charges,
					'search'=>$request->search,
					'categories'=>$categories,
					'category_code'=>$request->category_code,
			]);
	}

}
