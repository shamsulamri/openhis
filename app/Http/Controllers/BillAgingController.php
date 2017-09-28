<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BillAging;
use Log;
use DB;
use Session;
use App\BillHelper;

class BillAgingController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bill_agings = DB::table('bill_agings')
					->orderBy('age_amount')
					->paginate($this->paginateValue);
			return view('bill_agings.index', [
					'bill_agings'=>$bill_agings
			]);
	}

	public function create()
	{
			$bill_aging = new BillAging();
			return view('bill_agings.create', [
					'bill_aging' => $bill_aging,
				
					]);
	}

	public function store(Request $request) 
	{
			$bill_aging = new BillAging();
			$valid = $bill_aging->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bill_aging = new BillAging($request->all());
					$bill_aging->encounter_id = $request->encounter_id;
					$bill_aging->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bill_agings/id/'.$bill_aging->encounter_id);
			} else {
					return redirect('/bill_agings/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bill_aging = BillAging::findOrFail($id);
			return view('bill_agings.edit', [
					'bill_aging'=>$bill_aging,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$bill_aging = BillAging::findOrFail($id);
			$bill_aging->fill($request->input());


			$valid = $bill_aging->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bill_aging->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bill_agings/id/'.$id);
			} else {
					return view('bill_agings.edit', [
							'bill_aging'=>$bill_aging,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bill_aging = BillAging::findOrFail($id);
		return view('bill_agings.destroy', [
			'bill_aging'=>$bill_aging
			]);

	}
	public function destroy($id)
	{	
			BillAging::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bill_agings');
	}
	
	public function search(Request $request)
	{
			$bill_agings = DB::table('bill_agings')
					->where('age_amount','like','%'.$request->search.'%')
					->orWhere('encounter_id', 'like','%'.$request->search.'%')
					->orderBy('age_amount')
					->paginate($this->paginateValue);

			return view('bill_agings.index', [
					'bill_agings'=>$bill_agings,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bill_agings = DB::table('bill_agings')
					->where('encounter_id','=',$id)
					->paginate($this->paginateValue);

			return view('bill_agings.index', [
					'bill_agings'=>$bill_agings
			]);
	}

	public function enquiry(Request $request)
	{
			$bill_agings = DB::table('bill_agings')
					->where('age_amount','like','%'.$request->search.'%')
					->orWhere('encounter_id', 'like','%'.$request->search.'%')
					->orderBy('age_amount')
					->paginate($this->paginateValue);

			return view('bill_agings.enquiry', [
					'bill_agings'=>$bill_agings,
					'search'=>$request->search,
					'billHelper'=>new BillHelper(),
					]);
	}
}
