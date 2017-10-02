<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Payment;
use App\PaymentMethod;
use App\PaymentCredit;
use App\Patient;
use Log;
use DB;
use Session;
use Auth;
use App\Encounter;
use App\BillHelper;
use App\CreditCard;
use App\Nation;
use App\Sponsor;
use App\DojoUtility;
use App\User;
use App\Discharge;

class PaymentController extends Controller
{
	public $paginateValue=10;
	public $years = [];
	public $months = [];

	public function __construct()
	{
			$this->middleware('auth');

			$year = date("Y");
			while ($year<date("Y")+25) {
					$this->years[$year]=$year;
					$year++;
			}


			$this->months = Array(
					'01'=>'01',
					'02'=>'02',
					'03'=>'03',
					'04'=>'04',
					'05'=>'05',
					'06'=>'06',
					'07'=>'07',
					'08'=>'08',
					'09'=>'09',
					'10'=>'10',
					'11'=>'11',
					'12'=>'12');
	}

	public function index($id)
	{
			$payments = DB::table('payments as a')
					->select('payment_id', 'a.encounter_id', 'a.created_at', 'payment_amount','payment_name', 'payment_description')
					->leftJoin('patients as b', 'b.patient_id', '=',  'a.patient_id')
					->leftJoin('encounters as c', 'c.encounter_id','=', 'a.encounter_id')
					->leftJoin('payment_methods as d', 'd.payment_code', '=', 'a.payment_code')
					->where('a.patient_id','=', $id)
					->orderBy('a.encounter_id')
					->paginate($this->paginateValue);

			if (count($payments)==0) {
					//return redirect('/payments/create/'.$id);
			}

			$patient = Patient::find($id);
			return view('payments.index', [
					'patient_id'=>$id,
					'patient'=>$patient,
					'payments'=>$payments,
			]);
	}

	public function create($patient_id=null, $encounter_id=0 )
	{
			$payment = new Payment();
			$payment->encounter_id = $encounter_id;

			$encounter = Encounter::find($encounter_id);
			$patient = null;
			if ($patient_id) {
					$patient = Patient::find($patient_id);
			} else {
					$patient = $payment->encounter->patient;
			}

			$payment->patient_id = $patient->patient_id;

			
			$discharges = Discharge::select('discharges.encounter_id', 'discharge_date')
					->where('b.patient_id',$patient_id)
					->leftJoin('encounters as b', 'b.encounter_id', '=', 'discharges.encounter_id')
					->leftJoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->lists('encounter_id','encounter_id');

			return view('payments.create', [
					'payment' => $payment,
					'payment_methods' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'patient' => $patient,
					'encounter' => $encounter,
					'billHelper' => new BillHelper(),
					'encounter_id' => $encounter_id,
					'card' => CreditCard::all()->sortBy('card_name')->lists('card_name', 'card_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					'expiry_months'=>$this->months,
					'expiry_years'=>$this->years,
					'sponsor' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
					'discharges' => $discharges,
					'credit' => new PaymentCredit(),
					]);
	}

	public function store(Request $request) 
	{

			$payment_credit = new PaymentCredit();
			$valid = $payment_credit->validate($request->all(), $request->_method);


			if ($request->payment_code=='credit_card') {
					if ($valid->passes()) {
							$payment_credit = new PaymentCredit($request->all());
					} else {
							return redirect('/payments/create/'.$request->patient_id.'/'.$request->encounter_id)
									->withErrors($valid)
									->withInput();
					}
			}

			$payment = new Payment();
			$valid = $payment->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$payment = new Payment($request->all());
					$payment->user_id = Auth::user()->id;
					$payment->save();
					if ($payment->payment_code=='credit_card') {
							$payment_credit->payment_id = $payment->payment_id;
							$payment_credit->save();
					}

					Session::flash('message', 'Record successfully created.');
					if ($payment->encounter_id>0) {
							return redirect('/bill_items/'.$payment->encounter_id);
					} else {
							return redirect('/payments/'.$payment->patient_id);
					}
			} else {
					$payment = new Payment($request->all());
					return redirect('/payments/create/'.$payment->patient_id.'/'.$payment->encounter_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$payment = Payment::findOrFail($id);
			$patient = Patient::find($payment->patient_id);
			$credit = new PaymentCredit();
			if ($payment->payment_code=='credit_card') {
				$credit = PaymentCredit::where('payment_id', $id)->first();
			}

			$encounter = Encounter::find($payment->encounter_id);
			return view('payments.edit', [
					'payment'=>$payment,
					'payment_methods' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'patient' => $patient,
					'billHelper' => new BillHelper(),
					'encounter_id' => $payment->encounter_id,
					'encounter'=>$encounter,
					'sponsor' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
					'card' => CreditCard::all()->sortBy('card_name')->lists('card_name', 'card_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					'expiry_months'=>$this->months,
					'expiry_years'=>$this->years,
					'credit'=> $credit,
					]);
	}

	public function update(Request $request, $id) 
	{
			$payment = Payment::findOrFail($id);
			$payment->fill($request->input());


			$valid = $payment->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$payment->save();
					if ($payment->payment_code=='credit_card') {
						$payment_credit = PaymentCredit::where('payment_id', $id)->first();
						$payment_credit->fill($request->input());
						$payment_credit->save();
					}
					Session::flash('message', 'Record successfully updated.');
					if ($payment->encounter_id>0) {
							return redirect('/bill_items/'.$payment->encounter_id);
					} else {
							return redirect('/payments/'.$payment->patient_id);
					}
			} else {
					return view('payments.edit', [
							'payment'=>$payment,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$payment = Payment::findOrFail($id);
		return view('payments.destroy', [
			'payment'=>$payment,
			'patient'=>$payment->patient,
			]);

	}
	public function destroy($id)
	{	
			$payment = Payment::find($id);
			Payment::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			if ($payment->encounter_id>0) {
					return redirect('/bill_items/'.$payment->encounter_id);
			} else {
					return redirect('/payments/'.$payment->patient_id);
			}
	}
	
	public function search(Request $request)
	{
			$payments = DB::table('payments')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('payment_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('payments.index', [
					'payments'=>$payments,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$payments = DB::table('payments')
					->where('payment_id','=',$id)
					->paginate($this->paginateValue);

			return view('payments.index', [
					'payments'=>$payments
			]);
	}

	public function enquiry(Request $request)
	{
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$payments = Payment::select('payment_id','patient_name', 'patient_mrn', 'payments.encounter_id', 
					'payments.created_at', 'payment_amount','payment_name','name', 'payment_description')
					->leftJoin('patients as b', 'b.patient_id', '=',  'payments.patient_id')
					->leftJoin('encounters as c', 'c.encounter_id','=', 'payments.encounter_id')
					->leftJoin('payment_methods as d', 'd.payment_code', '=', 'payments.payment_code')
					->leftJoin('users as e', 'e.id', '=', 'payments.user_id')
					->orderBy('payments.encounter_id')
					->orderBy('payment_id');

			if (!empty($request->search)) {
					$payments = $payments->where(function ($query) use ($request) {
							$query->where('patient_mrn','like','%'.$request->search.'%')
								->orWhere('patient_name', 'like','%'.$request->search.'%')
								->orWhere('payments.encounter_id', 'like','%'.$request->search.'%');
					});
			}
			
			if (!empty($request->payment_code)) {
					$payments = $payments->where('payments.payment_code','=', $request->payment_code);
			}

			if (!empty($request->user_id)) {
					$payments = $payments->where('payments.user_id','=', $request->user_id);
			}

			if (!empty($date_start) && empty($request->date_end)) {
				$payments = $payments->where('payments.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$payments = $payments->where('payments.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$payments = $payments->whereBetween('payments.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if ($request->export_report) {
				DojoUtility::export_report($payments->get());
			}

			$payments = $payments->paginate($this->paginateValue);


			$users = User::orderby('name')
							->where('author_id', 6)
							->lists('name','id')
							->prepend('','');

			return view('payments.enquiry', [
					'payments'=>$payments,
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'payment_methods' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'payment_code'=>$request->payment_code,
					'search'=>$request->search,
					'users'=>$users,
					'user_id'=>$request->user_id,
			]);
	}
}
