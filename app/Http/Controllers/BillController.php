<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Bill;
use Log;
use DB;
use Session;
use App\Product;
use App\QueueLocation as Location;
use App\Encounter;
use App\PatientBilling;

class BillController extends Controller
{
	public $paginateValue=1000;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function generate($id) 
	{

			$bill_existing = DB::table('patient_billings')
								->where('encounter_id','=',$id);
								
			//$bill_existing->delete();

			
			$fields = 'encounter_id, order_id,order_exempted,b.tax_code, tax_rate, order_discount, a.product_code, sum(order_quantity_supply) as order_quantity_supply, sum(order_total) as order_total, order_sale_price';
			$bills = DB::table('orders as a')
					->selectRaw($fields)
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->where('encounter_id','=', $id)
					->groupBy('a.product_code')
					->orderBy('encounter_id')
					->get();

			foreach ($bills as $bill) {
					$patientBill = new PatientBilling();
					$patientBill->encounter_id = $bill->encounter_id;
					$patientBill->order_id = $bill->order_id;
					$patientBill->product_code = $bill->product_code;
					$patientBill->tax_code = $bill->tax_code;
					$patientBill->tax_rate = $bill->tax_rate;
					$patientBill->bill_quantity = $bill->order_quantity_supply;
					$patientBill->bill_unit_price = $bill->order_sale_price;
					$patientBill->bill_total = $bill->order_total;
					try {
							$patientBill->save();
					} catch (\Exception $e) {
							\Log::info($e->getMessage());
					}
					Log::info($bill->encounter_id);
			}

			return $bills;
	}

	public function index($id)
	{
			$encounter = Encounter::find($id);
			/*
			$bills = DB::table('orders as a')
					->selectRaw('encounter_id, order_id,order_exempted,b.tax_code, tax_rate, order_discount, product_name, sum(order_quantity_supply) as order_quantity_supply, sum(order_total) as order_total, order_sale_price')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->where('encounter_id','=', $id)
					->groupBy('a.product_code')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);
			*/	
			$bills = DB::table('patient_billings as a')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.tax_code')
					->where('encounter_id','=', $id)
					->orderBy('a.product_code')
					->paginate($this->paginateValue);

			$bill_total = DB::table('orders')
					->where('encounter_id','=', $id)
					->where('order_exempted','=',0)
					->sum('order_total');

			$gst_total = DB::table('orders as a')
					->selectRaw('sum(order_gst_total) as gst_sum, sum(order_quantity_supply*order_unit_price) as gst_amount, tax_code')
					->leftJoin('products as b','b.product_code','=', 'a.product_code')
					->where('encounter_id','=', $id)
					->where('order_exempted','=',0)
					->groupBy('tax_code')
					->get();
			
			$payments = DB::table('payments as a')
					->leftJoin('payment_methods as b', 'b.payment_code','=','a.payment_code')
					->where('encounter_id','=', $id)
					->paginate($this->paginateValue);
			
			$payment_total = DB::table('payments')
					->where('encounter_id','=', $id)
					->sum('payment_amount');

			return view('bills.index', [
					'bills'=>$bills,
					'bill_total'=>$bill_total,
					'patient' => $encounter->patient,
					'payments' => $payments,
					'payment_total' => $payment_total,
					'gst_total' => $gst_total,
			]);
	}

	public function create()
	{
			$bill = new Bill();
			return view('bills.create', [
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
			return view('bills.edit', [
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
							$bill->order_total = $bill->order_quantity_supply*$bill->order_sale_price;
						} else {
							$bill->order_total = $bill->order_sale_price;
						}
						break;
					default:
						$bill->order_total = $bill->order_quantity_supply*$bill->order_sale_price;
			}

			if ($bill->order_discount>0) {
					$bill->order_total = $bill->order_total * (1-($bill->order_discount/100));
			}

			$bill->order_exempted = $request->order_exempted ?: 0;

			$valid = $bill->validate($request->all(), $request->_method);

			if ($valid->passes()) {
				$bill->save();
				Session::flash('message', 'Record successfully updated.');
				return redirect('/bills/'.$bill->encounter_id);
			} else {
				return view('bills.edit', [
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
		$bill = Bill::findOrFail($id);
		return view('bills.destroy', [
			'bill'=>$bill,
			'patient'=>$bill->encounter->patient,
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
			$bills = DB::table('orders')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('order_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('bills.index', [
					'bills'=>$bills,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bills = DB::table('orders')
					->where('order_id','=',$id)
					->paginate($this->paginateValue);

			return view('bills.index', [
					'bills'=>$bills
			]);
	}
}
