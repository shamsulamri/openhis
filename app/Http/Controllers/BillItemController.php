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
use Carbon\Carbon;

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
						->where('encounter_id','=',$id)
						->get();

			Log::info($beds);
			foreach ($beds as $bed) {
					$bed_los = $bed->los;
					if ($bed_los<=0) $bed_los=1;
					$item = new BillItem();
					$item->encounter_id = $id;
					$item->order_id = 0;
					$item->product_code = $bed->product_code;
					$item->tax_code = $bed->tax_code;
					$item->tax_rate = $bed->tax_rate;
					$item->bill_quantity = $bed_los;
					$item->bill_unit_price = $bed->product_sale_price;
					$item->bill_total = $item->bill_unit_price*$item->bill_quantity;
					$item->bill_gst_unit = $bed->product_sale_price*($bed->tax_rate/100);
					try {
							$item->save();
					} catch (\Exception $e) {
							\Log::info($e->getMessage());
					}
			}
	}

	public function compileBill($id) 
	{

			Log::info("QQQQQ");
			$bill_existing = DB::table('bill_items')
								->where('encounter_id','=',$id);
			
			$fields = 'encounter_id, a.order_id,b.tax_code, tax_rate, order_discount, a.product_code, sum(order_quantity_supply) as order_quantity_supply, sum(order_total) as order_total, order_sale_price, order_gst_unit, order_completed';
			$orders = DB::table('orders as a')
					->selectRaw($fields)
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->leftjoin('order_cancellations as d', 'd.order_id', '=','a.order_id')
					->leftjoin('order_investigations as e', 'e.order_id', '=', 'a.order_id')
					->where('encounter_id','=', $id)
					->where('investigation_date','<=', Carbon::today())
					->whereNull('cancel_id')
					->groupBy('a.product_code')
					->orderBy('encounter_id')
					->get();

			foreach ($orders as $order) {
					$item = new BillItem();
					$item->encounter_id = $order->encounter_id;
					$item->order_id = $order->order_id;
					$item->product_code = $order->product_code;
					$item->tax_code = $order->tax_code;
					$item->tax_rate = $order->tax_rate;
					$item->bill_quantity = $order->order_quantity_supply;
					$item->bill_unit_price = $order->order_sale_price;
					$item->bill_total = $order->order_total;
					$item->bill_gst_unit = $order->order_gst_unit;
					try {
							$item->save();
					} catch (\Exception $e) {
							\Log::info($e->getMessage());
					}
			}

			$this->bedBills($id);
			$bills = DB::table('bill_items as a')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->where('encounter_id','=', $id)
					->orderBy('a.product_code')
					->paginate($this->paginateValue);

			return $bills;
	}

	public function index($id)
	{
			$encounter = Encounter::find($id);

			$bills = DB::table('bill_items as a')
					->select('bill_id','a.encounter_id','a.order_id','a.product_code','product_name','a.tax_code','a.tax_rate','bill_discount','bill_quantity','bill_unit_price','bill_total','bill_gst_unit','bill_exempted','order_completed')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->leftjoin('orders as d', 'd.order_id', '=', 'a.order_id')
					->where('a.encounter_id','=', $id)
					->orderBy('product_name');

			$bills = $bills->paginate($this->paginateValue);


			if ($bills->total()==0) {
				$bills=$this->compileBill($id);
			$bills = DB::table('bill_items as a')
					->select('bill_id','a.encounter_id','a.order_id','a.product_code','product_name','a.tax_code','a.tax_rate','bill_discount','bill_quantity','bill_unit_price','bill_total','bill_gst_unit','bill_exempted','order_completed')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->leftjoin('orders as d', 'd.order_id', '=', 'a.order_id')
					->where('a.encounter_id','=', $id)
					->orderBy('product_name')
					->paginate($this->paginateValue);
			}

			$pending = DB::table('bill_items as a')
					->select('bill_id')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->leftjoin('orders as d', 'd.order_id', '=', 'a.order_id')
					->where('a.encounter_id','=', $id)
					->where('order_completed','=',0)
					->count('bill_id');

			$bill_grand_total = DB::table('bill_items')
					->where('encounter_id','=', $id)
					->sum('bill_total');

			$gst_total = DB::table('bill_items as a')
					->selectRaw('sum(bill_quantity*bill_gst_unit) as gst_sum, sum(bill_quantity*(bill_unit_price-bill_gst_unit)) as gst_amount, tax_code')
					->where('encounter_id','=', $id)
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
}
