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
use Auth;

class LoanController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$loans = DB::table('loans')
					->orderBy('item_code')
					->paginate($this->paginateValue);
			return view('loans.index', [
					'loans'=>$loans
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
			return view('loans.edit', [
					'loan'=>$loan,
					'loan_status' => LoanStatus::all()->sortBy('loan_name')->lists('loan_name', 'loan_code')->prepend('',''),
					'item' => null,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					]);
	}

	public function update(Request $request, $id) 
	{
			$loan = Loan::findOrFail($id);
			$loan->fill($request->input());

			$loan->loan_recur = $request->loan_recur ?: 0;

			$valid = $loan->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$loan->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/loans/id/'.$id);
			} else {
					return view('loans.edit', [
							'loan'=>$loan,
							'item' => null,
							'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
							'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
							])
							->withErrors($valid);			
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
					->orderBy('item_code')
					->paginate($this->paginateValue);

			return view('loans.index', [
					'loans'=>$loans,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$loans = DB::table('loans')
					->where('loan_id','=',$id)
					->paginate($this->paginateValue);

			return view('loans.index', [
					'loans'=>$loans
			]);
	}

	public function request(Request $request, $id)
	{
			$product = Product::find($id);
			$loan = new Loan();
			$loan->loan_quantity=1;
			$loan->loan_code = 'request';
			$loan->loan_request_by = Auth::user()->id;
			$loan->ward_code = $request->cookie('ward');
			return view('loans.request', [
					'loan' => $loan,
					'loan_status' => LoanStatus::all()->sortBy('loan_name')->lists('loan_name', 'loan_code')->prepend('',''),
					'item' => null,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					'item_code' => $id,
					'product' => $product,
					]);
		
	}

	public function requestSubmit(Request $request, $id)
	{
			$loan = new Loan();
			$valid = $loan->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$loan = new Loan($request->all());
					$loan->loan_id = $request->loan_id;
					$loan->save();
					Session::flash('message', 'You request has been submitted.');
					return redirect('/loans/id/'.$loan->loan_id);
			} else {
					return redirect('/loans/request/'.$id)
							->withErrors($valid)
							->withInput();
			}

	}

}
