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
use App\DojoUtility;
use App\User;
use App\PatientType;
use App\Sponsor;
use App\Patient;
use App\EncounterType;

class DepositController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($id)
	{
			$patient = Patient::find($id);
			$deposits = Deposit::where('patient_id','=', $id)
					->leftjoin('payment_methods as b', 'deposits.payment_code','=','b.payment_code')
					->orderBy('deposit_id')
					->paginate($this->paginateValue);


			return view('deposits.index', [
					'deposits'=>$deposits,
					'patient'=>$patient,
			]);
	}

	public function create($id)
	{
			$patient = Patient::find($id);
			$encounter = $patient->activeEncounter();
			$deposit = new Deposit();

			return view('deposits.create', [
					'deposit' => $deposit,
					'payment' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'encounter' => $encounter,
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'patient'=>$patient,
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
					$datetime = DojoUtility::now();
					$deposit->deposit_date = DojoUtility::dateTimeWriteFormat($datetime);
					$deposit->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/deposits/index/'.$deposit->patient_id);
			} else {
					return redirect('/deposits/create/'.$request->patient_id)
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
					'patient'=>$deposit->patient,
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
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
					return redirect('/deposits/index/'.$deposit->patient_id);
			} else {
					return view('deposits.edit', [
							'deposit'=>$deposit,
							'payment' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
							'patient'=>$deposit->patient,
							'encounter'=>$deposit->encounter,
							'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$deposit = Deposit::findOrFail($id);
		return view('deposits.destroy', [
			'deposit'=>$deposit,
			'patient'=>$deposit->patient,
			'encounter'=>$deposit->encounter,
			]);

	}
	public function destroy($id)
	{	
			return "X";
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

	public function enquiry(Request $request)
	{
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$subquery = "
				select encounter_id, sum(order_unit_price*order_quantity_supply) as current_charges
				from orders
				where order_completed=1
				group by encounter_id";

			$deposits = Deposit::select(DB::raw('patient_name, patient_mrn, deposits.created_at as deposit_date, deposit_id, sum(deposit_amount) as total_deposit, 
					payment_name,deposits.encounter_id, b.created_at as encounter_date, current_charges'))
					->leftJoin('encounters as b', 'b.encounter_id', '=', 'deposits.encounter_id')
					->leftJoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->leftJoin('payment_methods as d', 'd.payment_code', '=', 'deposits.payment_code')
					->leftJoin(DB::raw('('.$subquery.') f'), function($join) {
							$join->on('deposits.encounter_id','=', 'f.encounter_id');
					})
					->groupBy('deposits.encounter_id')
					->orderBy('deposits.encounter_id');

			if (!empty($request->search)) {
					$deposits = $deposits->where(function ($query) use ($request) {
							$query->where('patient_mrn','like','%'.$request->search.'%')
								->orWhere('patient_name', 'like','%'.$request->search.'%')
								->orWhere('deposits.encounter_id', 'like','%'.$request->search.'%');
					});
			}

			if (!empty($request->user_id)) {
					$deposits = $deposits->where('deposits.user_id','=', $request->user_id);
			}

			if (!empty($request->sponsor_code)) {
					$deposits = $deposits->where('b.sponsor_code','=', $request->sponsor_code);
			}

			if (!empty($request->type_code)) {
					$deposits = $deposits->where('b.type_code','=', $request->type_code);
			}

			if (!empty($date_start) && empty($request->date_end)) {
				$deposits = $deposits->where('deposits.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$deposits = $deposits->where('deposits.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$deposits = $deposits->whereBetween('deposits.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if ($request->export_report) {
				DojoUtility::export_report($deposits->get());
			}

			$deposits = $deposits->paginate($this->paginateValue);


			return view('deposits.enquiry', [
					'deposits'=>$deposits,
					'search'=>$request->search,
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'payment_methods' => PaymentMethod::all()->sortBy('payment_name')->lists('payment_name', 'payment_code')->prepend('',''),
					'payment_code'=>$request->payment_code,
					]);
	}
}
