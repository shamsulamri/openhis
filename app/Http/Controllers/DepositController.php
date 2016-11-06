<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Deposit;
use Log;
use DB;
use Session;
use App\PaymentMethod;
use App\Encounter;
use Auth;

class DepositController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($id)
	{
			$deposits = DB::table('deposits as a')
					->leftjoin('payment_methods as b', 'a.payment_code','=','b.payment_code')
					->where('encounter_id','=', $id)
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			//if (count($deposits)==0) {
//					return redirect('/deposits/create/'.$id);
//			}

			$encounter = Encounter::find($id);
			return view('deposits.index', [
					'deposits'=>$deposits,
					'encounter'=>$encounter,
					'patient'=>$encounter->patient,
			]);
	}

	public function create($id)
	{
			$encounter = Encounter::find($id);
			$deposit = new Deposit();
			return view('deposits.create', [
					'deposit' => $deposit,
					'payment' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'encounter' => $encounter,
					'patient'=>$encounter->patient,
					]);
	}

	public function store(Request $request) 
	{
			$deposit = new Deposit();
			$valid = $deposit->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$deposit = new Deposit($request->all());
					$deposit->user_id = Auth::user()->id;
					$deposit->deposit_id = $request->deposit_id;
					$deposit->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/deposits/index/'.$deposit->encounter_id);
			} else {
					return redirect('/deposits/create/'.$request->encounter_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$deposit = Deposit::findOrFail($id);
			return view('deposits.edit', [
					'deposit'=>$deposit,
					'payment' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'encounter'=>$deposit->encounter,
					'patient'=>$deposit->encounter->patient,
					]);
	}

	public function update(Request $request, $id) 
	{
			$deposit = Deposit::findOrFail($id);
			$deposit->fill($request->input());


			$valid = $deposit->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$deposit->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/deposits/index/'.$deposit->encounter_id);
			} else {
					return view('deposits.edit', [
							'deposit'=>$deposit,
							'payment' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
							'patient'=>$deposit->encounter->patient,
							'encounter'=>$deposit->encounter,
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$deposit = Deposit::findOrFail($id);
		return view('deposits.destroy', [
			'deposit'=>$deposit,
			'patient'=>$deposit->encounter->patient,
			'encounter'=>$deposit->encounter,
			]);

	}
	public function destroy($id)
	{	
			$deposit = Deposit::findOrFail($id);
			Deposit::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/deposits/index/'.$deposit->encounter_id);
	}
	
	public function search(Request $request)
	{
			$deposits = DB::table('deposits')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('deposit_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('deposits.index', [
					'deposits'=>$deposits,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$deposits = DB::table('deposits')
					->where('deposit_id','=',$id)
					->paginate($this->paginateValue);

			return view('deposits.index', [
					'deposits'=>$deposits
			]);
	}
}
