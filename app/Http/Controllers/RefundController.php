<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Refund;
use Log;
use DB;
use Session;
use App\Patient;
use App\Bill;
use App\Deposit;
use App\DojoUtility;
use App\User;

class RefundController extends Controller
{
	public $paginateValue=10;
	public $refund_types=null;

	public function __construct()
	{
			$this->middleware('auth');
			$this->refund_types = array(''=>'','1'=>'Bill','2'=>'Deposit');
	}

	public function index()
	{
			$refunds = DB::table('refunds')
					->orderBy('refund_type')
					->paginate($this->paginateValue);
			return view('refunds.index', [
					'refunds'=>$refunds
			]);
	}

	public function transactions($patient_id)
	{
			$refunds = Refund::where('patient_id', $patient_id)
					->orderBy('refund_type')
					->paginate($this->paginateValue);
			return view('refunds.transaction', [
					'refunds'=>$refunds,
					'patient_id'=>$patient_id,
					'patient'=>Patient::find($patient_id),
			]);
	}

	public function create($patient_id)
	{
			$refund = new Refund();

			return view('refunds.create', [
					'refund' => $refund,
					'refund_types' => $this->refund_types,
					'patient'=>Patient::find($patient_id),
					]);
	}

	public function store(Request $request) 
	{
			$valid=null;

			$ids = explode(",", $request->refund_reference);
			$errors = [];
			foreach($ids as $id) {
				if ($request->refund_type==1) {
						$bill = Bill::where('id', $id)
								->leftJoin('encounters as b','b.encounter_id','=', 'bills.encounter_id')
								->where('patient_id', $request->patient_id)
								->first();
					if (empty($bill)) array_push($errors, $id);
				}
				if ($request->refund_type==2) {
						$bill = Deposit::where('deposit_id', $id)
								->leftJoin('encounters as b','b.encounter_id','=', 'deposits.encounter_id')
								->where('patient_id', $request->patient_id)
								->first();
					if (empty($bill)) array_push($errors, $id);
				}
			}

			if (count($errors)>0) {
					$valid['refund_reference'] = 'No reference found for '.implode(", ",$errors);
					return redirect('/refunds/create/'.$request->patient_id)
							->withErrors($valid)
							->withInput();
			}

			$refund = new Refund();
			$valid = $refund->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$refund = new Refund($request->all());
					$refund->refund_id = $request->refund_id;
					$refund->user_id = Auth::user()->id;
					$refund->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/refund/transactions/'.$refund->patient_id);
			} else {
					return redirect('/refunds/create/'.$refund->patient_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$refund = Refund::findOrFail($id);

			return view('refunds.edit', [
					'refund'=>$refund,
					'refund_types' => $this->refund_types,
					'patient'=>$refund->patient,
					]);
	}

	public function update(Request $request, $id) 
	{
			$refund = Refund::findOrFail($id);
			$refund->fill($request->input());

			$refund->refund_type = $request->refund_type ?: 0;

			$valid = $refund->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$refund->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/refund/transactions/'.$refund->patient_id);
			} else {
					return view('refunds.edit', [
							'refund'=>$refund,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$refund = Refund::findOrFail($id);
		return view('refunds.destroy', [
			'refund'=>$refund
			]);

	}
	public function destroy($id)
	{	
			$patient_id = Refund::find($id)->patient_id;
			Refund::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/refund/transactions/'.$patient_id);
	}
	
	public function search(Request $request)
	{
			$refunds = DB::table('refunds')
					->where('refund_type','like','%'.$request->search.'%')
					->orWhere('refund_id', 'like','%'.$request->search.'%')
					->orderBy('refund_type')
					->paginate($this->paginateValue);

			return view('refunds.index', [
					'refunds'=>$refunds,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$refunds = DB::table('refunds')
					->where('refund_id','=',$id)
					->paginate($this->paginateValue);

			return view('refunds.index', [
					'refunds'=>$refunds
			]);
	}

	public function enquiry(Request $request)
	{
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$refunds = Refund::select('refund_id','patient_name', 'patient_mrn','refund_type','refund_reference',
					'refunds.created_at', 'refund_amount','name', 'refund_description')
					->leftJoin('patients as b', 'b.patient_id', '=',  'refunds.patient_id')
					->leftJoin('users as e', 'e.id', '=', 'refunds.user_id')
					->orderBy('refund_id');

			if (!empty($request->search)) {
					$refunds = $refunds->where(function ($query) use ($request) {
							$query->where('patient_mrn','like','%'.$request->search.'%')
								->orWhere('patient_name', 'like','%'.$request->search.'%');
					});
			}
			
			if (!empty($request->payment_code)) {
					$refunds = $refunds->where('refunds.payment_code','=', $request->payment_code);
			}

			if (!empty($request->user_id)) {
					$refunds = $refunds->where('refunds.user_id','=', $request->user_id);
			}

			if (!empty($date_start) && empty($request->date_end)) {
				$refunds = $refunds->where('refunds.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$refunds = $refunds->where('refunds.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$refunds = $refunds->whereBetween('refunds.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if ($request->export_report) {
				DojoUtility::export_report($refunds->get());
			}

			$refunds = $refunds->paginate($this->paginateValue);


			$users = User::orderby('name')
							->where('author_id', 6)
							->lists('name','id')
							->prepend('','');

			return view('refunds.enquiry', [
					'refunds'=>$refunds,
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'search'=>$request->search,
					'users'=>$users,
					'user_id'=>$request->user_id,
			]);
	}
}
