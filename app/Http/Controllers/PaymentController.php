<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Payment;
use App\PaymentMethod;
use App\Patient;
use Log;
use DB;
use Session;
use Auth;
use App\Encounter;
use App\BillHelper;

class PaymentController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
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
					return redirect('/payments/create/'.$id);
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

			return view('payments.create', [
					'payment' => $payment,
					'payment_methods' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'patient' => $patient,
					'encounter' => $encounter,
					'billHelper' => new BillHelper(),
					'encounter_id' => $encounter_id,

					]);
	}

	public function store(Request $request) 
	{
			$payment = new Payment();
			$valid = $payment->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$payment = new Payment($request->all());
					$payment->user_id = Auth::user()->id;
					$payment->save();
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
			return view('payments.edit', [
					'payment'=>$payment,
					'payment_methods' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'patient' => $patient,
					'billHelper' => new BillHelper(),
					'encounter_id' => $payment->encounter_id,
					]);
	}

	public function update(Request $request, $id) 
	{
			$payment = Payment::findOrFail($id);
			$payment->fill($request->input());


			$valid = $payment->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$payment->save();
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
}
