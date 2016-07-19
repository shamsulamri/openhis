<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\LoanStatus;
use Log;
use DB;
use Session;


class LoanStatusController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$loan_statuses = DB::table('ref_loan_statuses')
					->orderBy('loan_code')
					->paginate($this->paginateValue);
			return view('loan_statuses.index', [
					'loan_statuses'=>$loan_statuses
			]);
	}

	public function create()
	{
			$loan_status = new LoanStatus();
			return view('loan_statuses.create', [
					'loan_status' => $loan_status,
				
					]);
	}

	public function store(Request $request) 
	{
			$loan_status = new LoanStatus();
			$valid = $loan_status->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$loan_status = new LoanStatus($request->all());
					$loan_status->loan_code = $request->loan_code;
					$loan_status->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/loan_statuses/id/'.$loan_status->loan_code);
			} else {
					return redirect('/loan_statuses/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$loan_status = LoanStatus::findOrFail($id);
			return view('loan_statuses.edit', [
					'loan_status'=>$loan_status,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$loan_status = LoanStatus::findOrFail($id);
			$loan_status->fill($request->input());


			$valid = $loan_status->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$loan_status->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/loan_statuses/id/'.$id);
			} else {
					return view('loan_statuses.edit', [
							'loan_status'=>$loan_status,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$loan_status = LoanStatus::findOrFail($id);
		return view('loan_statuses.destroy', [
			'loan_status'=>$loan_status
			]);

	}
	public function destroy($id)
	{	
			LoanStatus::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/loan_statuses');
	}
	
	public function search(Request $request)
	{
			$loan_statuses = DB::table('ref_loan_statuses')
					->where('loan_code','like','%'.$request->search.'%')
					->orWhere('loan_code', 'like','%'.$request->search.'%')
					->orderBy('loan_code')
					->paginate($this->paginateValue);

			return view('loan_statuses.index', [
					'loan_statuses'=>$loan_statuses,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$loan_statuses = DB::table('ref_loan_statuses')
					->where('loan_code','=',$id)
					->paginate($this->paginateValue);

			return view('loan_statuses.index', [
					'loan_statuses'=>$loan_statuses
			]);
	}
}
