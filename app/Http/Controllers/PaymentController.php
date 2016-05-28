<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Payment;
use App\PaymentMethod;
use Log;
use DB;
use Session;
use Auth;

class PaymentController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$payments = DB::table('payments')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);
			return view('payments.index', [
					'payments'=>$payments,
			]);
	}

	public function create($id)
	{
			$payment = new Payment();
			$payment->encounter_id = $id;
			return view('payments.create', [
					'payment' => $payment,
					'payment_methods' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'patient' => $payment->encounter->patient,
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
					return redirect('/bills/'.$payment->encounter_id);
			} else {
					return redirect('/payments/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$payment = Payment::findOrFail($id);
			return view('payments.edit', [
					'payment'=>$payment,
					'payment_methods' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'patient' => $payment->encounter->patient,
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
					return redirect('/bills/'.$payment->encounter_id);
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
			'payment'=>$payment
			]);

	}
	public function destroy($id)
	{	
			$payment = Payment::find($id);
			Payment::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bills/'.$payment->encounter_id);
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
