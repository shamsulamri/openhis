<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PaymentCredit;
use Log;
use DB;
use Session;
use App\CreditCard as Card;
use App\Nation;


class PaymentCreditController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$payment_credits = DB::table('payment_credits')
					->orderBy('card_code')
					->paginate($this->paginateValue);
			return view('payment_credits.index', [
					'payment_credits'=>$payment_credits
			]);
	}

	public function create()
	{
			$payment_credit = new PaymentCredit();
			return view('payment_credits.create', [
					'payment_credit' => $payment_credit,
					'card' => Card::all()->sortBy('card_name')->lists('card_name', 'card_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$payment_credit = new PaymentCredit();
			$valid = $payment_credit->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$payment_credit = new PaymentCredit($request->all());
					$payment_credit->credit_id = $request->credit_id;
					$payment_credit->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/payment_credits/id/'.$payment_credit->credit_id);
			} else {
					return redirect('/payment_credits/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$payment_credit = PaymentCredit::findOrFail($id);
			return view('payment_credits.edit', [
					'payment_credit'=>$payment_credit,
					'card' => Card::all()->sortBy('card_name')->lists('card_name', 'card_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$payment_credit = PaymentCredit::findOrFail($id);
			$payment_credit->fill($request->input());


			$valid = $payment_credit->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$payment_credit->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/payment_credits/id/'.$id);
			} else {
					return view('payment_credits.edit', [
							'payment_credit'=>$payment_credit,
					'card' => Card::all()->sortBy('card_name')->lists('card_name', 'card_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$payment_credit = PaymentCredit::findOrFail($id);
		return view('payment_credits.destroy', [
			'payment_credit'=>$payment_credit
			]);

	}
	public function destroy($id)
	{	
			PaymentCredit::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/payment_credits');
	}
	
	public function search(Request $request)
	{
			$payment_credits = DB::table('payment_credits')
					->where('card_code','like','%'.$request->search.'%')
					->orWhere('credit_id', 'like','%'.$request->search.'%')
					->orderBy('card_code')
					->paginate($this->paginateValue);

			return view('payment_credits.index', [
					'payment_credits'=>$payment_credits,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$payment_credits = DB::table('payment_credits')
					->where('credit_id','=',$id)
					->paginate($this->paginateValue);

			return view('payment_credits.index', [
					'payment_credits'=>$payment_credits
			]);
	}
}
