<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BedTransaction;
use Log;
use DB;
use Session;


class BedTransactionController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bed_transactions = DB::table('bed_transactions')
					->orderBy('transaction_name')
					->paginate($this->paginateValue);
			return view('bed_transactions.index', [
					'bed_transactions'=>$bed_transactions
			]);
	}

	public function create()
	{
			$bed_transaction = new BedTransaction();
			return view('bed_transactions.create', [
					'bed_transaction' => $bed_transaction,
				
					]);
	}

	public function store(Request $request) 
	{
			$bed_transaction = new BedTransaction();
			$valid = $bed_transaction->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bed_transaction = new BedTransaction($request->all());
					$bed_transaction->transaction_code = $request->transaction_code;
					$bed_transaction->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bed_transactions/id/'.$bed_transaction->transaction_code);
			} else {
					return redirect('/bed_transactions/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bed_transaction = BedTransaction::findOrFail($id);
			return view('bed_transactions.edit', [
					'bed_transaction'=>$bed_transaction,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$bed_transaction = BedTransaction::findOrFail($id);
			$bed_transaction->fill($request->input());


			$valid = $bed_transaction->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bed_transaction->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bed_transactions/id/'.$id);
			} else {
					return view('bed_transactions.edit', [
							'bed_transaction'=>$bed_transaction,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bed_transaction = BedTransaction::findOrFail($id);
		return view('bed_transactions.destroy', [
			'bed_transaction'=>$bed_transaction
			]);

	}
	public function destroy($id)
	{	
			BedTransaction::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bed_transactions');
	}
	
	public function search(Request $request)
	{
			$bed_transactions = DB::table('bed_transactions')
					->where('transaction_name','like','%'.$request->search.'%')
					->orWhere('transaction_code', 'like','%'.$request->search.'%')
					->orderBy('transaction_name')
					->paginate($this->paginateValue);

			return view('bed_transactions.index', [
					'bed_transactions'=>$bed_transactions,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bed_transactions = DB::table('bed_transactions')
					->where('transaction_code','=',$id)
					->paginate($this->paginateValue);

			return view('bed_transactions.index', [
					'bed_transactions'=>$bed_transactions
			]);
	}
}
