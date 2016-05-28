<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BillItem as Bill;
use Log;
use DB;
use Session;
use App\Product;
use App\QueueLocation as Location;
use App\Encounter;
use App\DojoUtility;

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
			return redirect('/bills/'.$id);
	}

	public function bedBills($id) {
			$beds = DB::table('bed_movements as a')
						->selectRaw('*, datediff(now(),move_date) as los')
						->leftjoin('products as b', 'move_to','=', 'product_code')
						->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
						->where('encounter_id','=',$id)
						->get();

			foreach ($beds as $bed) {
					$bed_los = $bed->los;
					if ($bed_los==0) $bed_los=1;
					$bill = new Bill();
					$bill->encounter_id = $id;
					$bill->order_id = 0;
					$bill->product_code = $bed->product_code;
					$bill->tax_code = $bed->tax_code;
					$bill->tax_rate = $bed->tax_rate;
					$bill->bill_quantity = $bed_los;
					$bill->bill_unit_price = $bed->product_sale_price;
					$bill->bill_total = $bill->bill_unit_price*$bill->bill_quantity;
					$bill->bill_gst_unit = $bed->product_sale_price*($bed->tax_rate/100);
					Log::info('->'.$bill);
					try {
							$bill->save();
					} catch (\Exception $e) {
							\Log::info($e->getMessage());
					}
			}
	}

	public function compileBill($id) 
	{

			$bill_existing = DB::table('bill_items')
								->where('encounter_id','=',$id);
			
			$fields = 'encounter_id, order_id,b.tax_code, tax_rate, order_discount, a.product_code, sum(order_quantity_supply) as order_quantity_supply, sum(order_total) as order_total, order_sale_price, order_gst_unit';
			$bills = DB::table('orders as a')
					->selectRaw($fields)
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->where('encounter_id','=', $id)
					->groupBy('a.product_code')
					->orderBy('encounter_id')
					->get();

			foreach ($bills as $bill) {
					$patientBill = new Bill();
					$patientBill->encounter_id = $bill->encounter_id;
					$patientBill->order_id = $bill->order_id;
					$patientBill->product_code = $bill->product_code;
					$patientBill->tax_code = $bill->tax_code;
					$patientBill->tax_rate = $bill->tax_rate;
					$patientBill->bill_quantity = $bill->order_quantity_supply;
					$patientBill->bill_unit_price = $bill->order_sale_price;
					$patientBill->bill_total = $bill->order_total;
					$patientBill->bill_gst_unit = $bill->order_gst_unit;
					try {
							$patientBill->save();
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
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->where('encounter_id','=', $id)
					->orderBy('a.product_code')
					->paginate($this->paginateValue);

			if ($bills->total()==0) {
				$bills=$this->compileBill($id);
			}

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

			$deposit_total = DB::table('deposits')
					->where('encounter_id','=', $id)
					->sum('deposit_amount');

			return view('bill_items.index', [
					'bills'=>$bills,
					'bill_grand_total'=>$bill_grand_total,
					'patient' => $encounter->patient,
					'payments' => $payments,
					'payment_total' => $payment_total,
					'gst_total' => $gst_total,
					'encounter' => $encounter,
					'encounter_id' => $id,
					'deposit_total' => $deposit_total,
			]);
	}

	public function create()
	{
			$bill = new Bill();
			return view('bill_items.create', [
					'bill' => $bill,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$bill = new Bill();
			$valid = $bill->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bill = new Bill($request->all());
					$bill->order_id = $request->order_id;
					$bill->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bills/id/'.$bill->order_id);
			} else {
					return redirect('/bills/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bill = Bill::findOrFail($id);
			return view('bill_items.edit', [
					'bill'=>$bill,
					'product' => Product::find($bill->product_code),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'patient' => $bill->encounter->patient,
					]);
	}

	public function update(Request $request, $id) 
	{
			$bill = Bill::findOrFail($id);
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
				$bill->save();
				Session::flash('message', 'Record successfully updated.');
				return redirect('/bills/'.$bill->encounter_id);
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
		$bill = Bill::find($id);
		return view('bill_items.destroy', [
			'bill'=>$bill,
			'patient'=>$bill->encounter->patient,
			]);

	}

	public function destroy($id)
	{	
		$bill = Bill::find($id);
			Bill::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bills/'.$bill->encounter_id);
	}
	
	public function reload($id)
	{
		$bill = Bill::where('encounter_id','=',$id)->first();
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
