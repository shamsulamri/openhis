<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Loan;
use Log;
use DB;
use Session;
use App\Ward;
use App\Period;
use Carbon\Carbon;
use App\LoanStatus;
use App\Product;
use App\Patient;
use Auth;

class LoanController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$loans=null;
			$is_folder=False;
			$title="Loan List";
			$loan_status=null;
			$type=null;
			if (!empty($request->type)) $type=$request->type;

			if ($request->type=='folder') {
					$is_folder=True;
					$title="Folder List";
					$loan_status = LoanStatus::whereIn('loan_code',['request', 'lend', 'return','lost'])
							->orderBy('loan_name')->lists('loan_name', 'loan_code')->prepend('','');

					$loans = Loan::where('loan_code', '<>', 'exchanged')
							->whereNotNull('loan_is_folder');
			} else {
					$loan_status = LoanStatus::all()->sortBy('loan_name')->lists('loan_name', 'loan_code')->prepend('','');
					$loans = Loan::where('loan_code','<>', 'return');
			}

			if (!empty($request->search)) {
					$loans = Loan::where('item_code','like','%'.$request->search.'%');
			}

			if (!empty($request->loan_code)) $loans=$loans->where('loan_code','=',$request->loan_code);
			if (!empty($request->ward_code)) $loans=$loans->where('ward_code','=',$request->ward_code);

			$loans = $loans->orderBy('created_at', 'desc')
							->paginate($this->paginateValue);

			return view('loans.index', [
					'loans'=>$loans,
					'loan_status' => $loan_status,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward_code'=>$request->ward_code,
					'loan_code'=>$request->loan_code,
					'is_folder'=>$is_folder,
					'title'=>$title,
					'search'=>$request->search,
			]);
	}

	public function ward(Request $request)
	{
			$loans = Loan::where('ward_code', $request->cookie('ward'))
							->where('loan_code','<>', 'return')
							->where('loan_code', '<>', 'exchanged')
							->orderBy('created_at', 'desc')
							->paginate($this->paginateValue);
			return view('loans.ward', [
					'loans'=>$loans,
					'loan_status' => LoanStatus::all()->sortBy('loan_name')->lists('loan_name', 'loan_code')->prepend('',''),
					'ward' => Ward::where('ward_code', $request->cookie('ward'))->first(),
					'loan_code' => null,
			]);
	}
	public function create()
	{
			$loan = new Loan();
			return view('loans.create', [
					'loan' => $loan,
					'loan_status' => LoanStatus::all()->sortBy('loan_name')->lists('loan_name', 'loan_code')->prepend('',''),
					'item' => null,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					]);
	}


	public function store(Request $request) 
	{
			$loan = new Loan();
			$valid = $loan->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$loan = new Loan($request->all());
					$loan->loan_id = $request->loan_id;
					$loan->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/loans/id/'.$loan->loan_id);
			} else {
					return redirect('/loans/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$loan = Loan::findOrFail($id);

			$loan_status=null;
			if ($loan->loan_is_folder) {
					$loan_status = LoanStatus::whereIn('loan_code',['request', 'lend', 'return','lost'])
							->orderBy('loan_name')->lists('loan_name', 'loan_code');
			} else {
					$loan_status = LoanStatus::all()->sortBy('loan_name')->lists('loan_name', 'loan_code');
			}

			return view('loans.edit', [
					'loan'=>$loan,
					'loan_status' => $loan_status,
					'item' => null,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					'today' =>date('d/m/Y', strtotime(Carbon::now())),
					'today_datetime' =>date('d/m/Y H:i', strtotime(Carbon::now())),
					]);
	}

	public function update(Request $request, $id) 
	{
			$loan = Loan::findOrFail($id);
			$loan->fill($request->input());

			$loan->loan_recur = $request->loan_recur ?: 0;

			if ($loan->loan_code != 'exchange') {
				if (!empty($loan->loan_return)) $loan->loan_code='return';
			}

			if ($loan->loan_code == 'exchange') {
				if (!empty($loan->loan_return)) $loan->loan_code='exchanged';
			}
			$valid = $loan->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$loan->save();
					Session::flash('message', 'Record successfully updated.');
					if ($loan->loan_is_folder) {
							return redirect('/loans?type=folder');
					} else {
							return redirect('/loans');
					}
			} else {
					return redirect('/loans/'.$id.'/edit')
						->withErrors($valid)
						->withInput();
			}
	}
	
	public function delete($id)
	{
		$loan = Loan::findOrFail($id);
		return view('loans.destroy', [
			'loan'=>$loan
			]);

	}
	public function destroy($id)
	{	
			Loan::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/loans');
	}
	
	public function search(Request $request)
	{
			$loans = DB::table('loans')
					->where('item_code','like','%'.$request->search.'%')
					->orWhere('loan_id', 'like','%'.$request->search.'%')
					->orWhere('ward_code', 'like','%'.$request->ward_code.'%')
					->orWhere('loan_code', 'like','%'.$request->loan_code.'%')
					->orderBy('created_at')
					->paginate($this->paginateValue);

			$loans = Loan::where('item_code','like','%'.$request->search.'%');

			if (!empty($request->loan_code)) $loans=$loans->where('loan_code','=',$request->loan_code);
			if (!empty($request->ward_code)) $loans=$loans->where('ward_code','=',$request->ward_code);

			//$loans = $loans->toSql();
			//dd($loans);			
			$loans = $loans->orderBy('created_at','desc')
							->paginate($this->paginateValue);

			return view('loans.index', [
					'loans'=>$loans,
					'search'=>$request->search,
					'loan_status' => LoanStatus::all()->sortBy('loan_name')->lists('loan_name', 'loan_code')->prepend('',''),
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward_code'=>$request->ward_code,
					'loan_code'=>$request->loan_code,
					]);
	}

	public function request_search(Request $request)
	{
			$loans = Loan::where('loan_code', 'like','%'.$request->loan_code.'%')
					->where('ward_code','=',$request->cookie('ward'))
					->where(function ($query) use ($request) {
							$query->where('item_code','like','%'.$request->search.'%');
					});
					/*
					->where('item_code','like','%'.$request->search.'%')
					->orWhere('loan_id', 'like','%'.$request->search.'%')
					->orWhere('loan_code', 'like','%'.$request->loan_code.'%')
					->orderBy('item_code')
					->paginate($this->paginateValue);
					*/


			//$loans = $loans->toSql();
			//dd($loans);			

			$loans = $loans->orderBy('created_at', 'desc')
							->paginate($this->paginateValue);

			return view('loans.ward', [
					'loans'=>$loans,
					'search'=>$request->search,
					'loan_status' => LoanStatus::all()->sortBy('loan_name')->lists('loan_name', 'loan_code')->prepend('',''),
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward_code'=>$request->ward_code,
					'loan_code'=>$request->loan_code,
					'ward' => Ward::where('ward_code', $request->cookie('ward'))->first(),
					]);
	}

	public function request(Request $request, $id)
	{
			$loan_status = LoanStatus::where('loan_code','request')
								->orWhere('loan_code', 'exchange')
								->orderBy('loan_name')
								->lists('loan_name', 'loan_code');


			$product = Product::find($id);
			$loan = new Loan();
			$loan->item_code = $id;
			$loan->loan_quantity=1;
			$loan->loan_code = 'request';
			$loan->loan_request_by = Auth::user()->id;
			$loan->ward_code = $request->cookie('ward');

			$is_folder = False;
			$title="New Loan";
			$patient=null;
			$is_exchange=False;
			if (!empty($request->loan)) {
					$is_exchange=True;
					$title="Exchange Request";
					$loan = Loan::find($id);
					$loan->loan_code = 'exchange';
					$loan->loan_description = "";
					$loan->loan_recur=null;
					$product = Product::find($loan->item_code);
			}
			if ($request->type=='folder') {
					$title="Folder Request";
					$is_folder=True;
					$patient = Patient::where('patient_mrn', $id)->first();
			}

			return view('loans.request', [
					'loan' => $loan,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward' => Ward::where('ward_code', $request->cookie('ward'))->first(),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					'loan_status' => $loan_status,
					'item_code' => $id,
					'product' => $product,
					'url'=>'loans/request/'.$id,
					'title'=>$title,
					'is_folder'=>$is_folder,
					'patient'=>$patient,
					]);
		
	}

	public function requestSubmit(Request $request, $id)
	{
			$loan = new Loan();
			$valid = $loan->validate($request->all(), $request->_method);

			$loan->loan_is_folder = $request->loan_is_folder ?: 0;

			if ($valid->passes()) {
					$loan = new Loan($request->all());
					$loan->loan_id = $request->loan_id;
					$loan->save();
					Session::flash('message', 'You request has been submitted.');
					return redirect('/loans/ward');
			} else {
					return redirect('/loans/request/'.$id)
							->withErrors($valid)
							->withInput();
			}

	}

	public function requestEdit(Request $request, $id) 
	{
			$loan = Loan::find($id);
			$product = Product::find($loan->item_code);
			$loan_status = LoanStatus::where('loan_code','<>', 'exchanged')
								->orderBy('loan_name')
								->lists('loan_name', 'loan_code');


			return view('loans.request', [
					'loan' => $loan,
					'loan_status' => $loan_status,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward' => Ward::where('ward_code', $request->cookie('ward'))->first(),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					'item_code' => $loan->item_code,
					'product' => $product,
					'url'=>'loans/request/'.$loan->loan_id.'/edit',
					'title'=>'Edit Loan',
					'is_folder' => null,
					]);
	}

	public function requestUpdate(Request $request, $id)
	{
			$loan = Loan::findOrFail($id);
			$loan->fill($request->input());

			$loan->loan_recur = $request->loan_recur ?: 0;
			$loan->loan_is_folder = $request->loan_is_folder ?: 0;

			$valid = $loan->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$loan->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/loans/ward');
			} else {
					return redirect('/loans/request/'.$id.'/edit')
						->withErrors($valid)
						->withInput();
			}
	}


	public function requestDelete($id)
	{
		$loan = Loan::findOrFail($id);
		return view('loans.destroy_request', [
			'loan'=>$loan
			]);

	}
	public function requestDestroy($id)
	{	
			Loan::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/loans/ward');
	}
}
