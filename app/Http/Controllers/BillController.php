<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

class BillController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bills = DB::table('bills')
					->orderBy('bill_grand_total')
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
			$bills = DB::table('bills')
					->where('bill_grand_total','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
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
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$subquery = "select encounter_id, sum(payment_amount) as total_paid from payments group by encounter_id";
			$bills = Bill::select(DB::raw('bills.id,patient_name, patient_mrn, bills.encounter_id, d.discharge_date,sponsor_name, bill_grand_total, bill_payment_total, bill_deposit_total, 
					total_paid, format(bill_grand_total-IFNULL(bill_deposit_total,0)-IFNULL(total_paid,0),2) as bill_outstanding, name'))
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

			if (!empty($request->type_code)) {
					$bills = $bills->where('b.type_code','=', $request->type_code);
			}

			if (!empty($date_start) && empty($request->date_end)) {
				$bills = $bills->where('bills.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$bills = $bills->where('bills.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$bills = $bills->whereBetween('bills.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if ($request->export_report) {
				DojoUtility::export_report($bills->get());
			}

			$bills = $bills->orderBy('bill_outstanding','desc');
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
			$encounter->fill($request->input());
			$valid = $encounter->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					if ($encounter->type_code=='public') {
						$encounter->sponsor_code = null;
						$encounter->sponsor_id=null;
					}
					$encounter->save();

					DB::table('bill_items')
						->where('encounter_id','=',$encounter->encounter_id)
						->delete();

					DB::table('payments')
						->where('encounter_id','=',$encounter->encounter_id)
						->delete();

					Session::flash('message', 'Record successfully updated.');
					return redirect('/bill_items/'.$encounter->encounter_id);
			} else {
					//return $valid->messages();
					return redirect('/bill/bill_edit/'.$id)
							->withErrors($valid)
							->withInput();
			}
	}
}
