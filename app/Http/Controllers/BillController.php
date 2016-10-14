<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Bill;
use App\Encounter;
use Log;
use DB;
use Session;
use Auth;

class BillController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bills = DB::table('bills')
					->orderBy('bill_grand_total')
					->paginate($this->paginateValue);
			return view('bills.index', [
					'bills'=>$bills
			]);
	}

	public function create()
	{
			$bill = new Bill();
			return view('bills.create', [
					'bill' => $bill,
					]);
	}

	public function store(Request $request) 
	{
			$bill = new Bill();
			$valid = $bill->validate($request->all(), $request->_method);


			if ($valid->passes()) {
					$bill = new Bill($request->all());
					$bill->user_id = Auth::user()->id;
					$bill->id = $request->id;
					try {
							$bill->save();
					} catch (\Exception $e) {
							\Log::info($e->getMessage());
					} finally {
							$encounter = Encounter::find($bill->encounter_id);
							return view('bills.post', [
									'patient'=>$encounter->patient,
									'encounter'=>$encounter,
							]);
					}
			} else {
					return redirect('/bills/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bill = Bill::findOrFail($id);
			return view('bills.edit', [
					'bill'=>$bill,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$bill = Bill::findOrFail($id);
			$bill->fill($request->input());


			$valid = $bill->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bill->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bills/id/'.$id);
			} else {
					return view('bills.edit', [
							'bill'=>$bill,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bill = Bill::findOrFail($id);
		return view('bills.destroy', [
			'bill'=>$bill
			]);

	}
	public function destroy($id)
	{	
			Bill::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bills');
	}
	
	public function search(Request $request)
	{
			$bills = DB::table('bills')
					->where('bill_grand_total','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
					->orderBy('bill_grand_total')
					->paginate($this->paginateValue);

			return view('bills.index', [
					'bills'=>$bills,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bills = DB::table('bills')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('bills.index', [
					'bills'=>$bills
			]);
	}
}
