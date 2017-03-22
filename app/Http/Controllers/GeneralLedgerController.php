<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\GeneralLedger;
use Log;
use DB;
use Session;


class GeneralLedgerController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$general_ledgers = DB::table('general_ledgers')
					->orderBy('gl_name')
					->paginate($this->paginateValue);
			return view('general_ledgers.index', [
					'general_ledgers'=>$general_ledgers
			]);
	}

	public function create()
	{
			$general_ledger = new GeneralLedger();
			return view('general_ledgers.create', [
					'general_ledger' => $general_ledger,
				
					]);
	}

	public function store(Request $request) 
	{
			$general_ledger = new GeneralLedger();
			$valid = $general_ledger->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$general_ledger = new GeneralLedger($request->all());
					$general_ledger->gl_code = $request->gl_code;
					$general_ledger->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/general_ledgers/id/'.$general_ledger->gl_code);
			} else {
					return redirect('/general_ledgers/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$general_ledger = GeneralLedger::findOrFail($id);
			return view('general_ledgers.edit', [
					'general_ledger'=>$general_ledger,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$general_ledger = GeneralLedger::findOrFail($id);
			$general_ledger->fill($request->input());


			$valid = $general_ledger->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$general_ledger->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/general_ledgers/id/'.$id);
			} else {
					return view('general_ledgers.edit', [
							'general_ledger'=>$general_ledger,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$general_ledger = GeneralLedger::findOrFail($id);
		return view('general_ledgers.destroy', [
			'general_ledger'=>$general_ledger
			]);

	}
	public function destroy($id)
	{	
			GeneralLedger::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/general_ledgers');
	}
	
	public function search(Request $request)
	{
			$general_ledgers = DB::table('general_ledgers')
					->where('gl_name','like','%'.$request->search.'%')
					->orWhere('gl_code', 'like','%'.$request->search.'%')
					->orderBy('gl_name')
					->paginate($this->paginateValue);

			return view('general_ledgers.index', [
					'general_ledgers'=>$general_ledgers,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$general_ledgers = DB::table('general_ledgers')
					->where('gl_code','=',$id)
					->paginate($this->paginateValue);

			return view('general_ledgers.index', [
					'general_ledgers'=>$general_ledgers
			]);
	}
}
