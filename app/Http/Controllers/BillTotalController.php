<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BillTotal;
use Log;
use DB;
use Session;


class BillTotalController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bill_totals = BillTotal::orderBy('bill_total')
					->paginate($this->paginateValue);

			return view('bill_totals.index', [
					'bill_totals'=>$bill_totals
			]);
	}

	public function create()
	{
			$bill_total = new BillTotal();
			return view('bill_totals.create', [
					'bill_total' => $bill_total,
				
					]);
	}

	public function store(Request $request) 
	{
			$bill_total = new BillTotal();
			$valid = $bill_total->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bill_total = new BillTotal($request->all());
					$bill_total->encounter_id = $request->encounter_id;
					$bill_total->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bill_totals/id/'.$bill_total->encounter_id);
			} else {
					return redirect('/bill_totals/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bill_total = BillTotal::findOrFail($id);
			return view('bill_totals.edit', [
					'bill_total'=>$bill_total,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$bill_total = BillTotal::findOrFail($id);
			$bill_total->fill($request->input());


			$valid = $bill_total->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bill_total->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bill_totals/id/'.$id);
			} else {
					return view('bill_totals.edit', [
							'bill_total'=>$bill_total,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bill_total = BillTotal::findOrFail($id);
		return view('bill_totals.destroy', [
			'bill_total'=>$bill_total
			]);

	}
	public function destroy($id)
	{	
			BillTotal::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bill_totals');
	}
	
	public function search(Request $request)
	{
			$bill_totals = BillTotal::where('bill_total','like','%'.$request->search.'%')
					->orWhere('encounter_id', 'like','%'.$request->search.'%')
					->orderBy('bill_total')
					->paginate($this->paginateValue);

			return view('bill_totals.index', [
					'bill_totals'=>$bill_totals,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bill_totals = BillTotal::where('encounter_id','=',$id)
					->paginate($this->paginateValue);

			return view('bill_totals.index', [
					'bill_totals'=>$bill_totals
			]);
	}
}
