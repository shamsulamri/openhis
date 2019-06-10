<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BillDiscount;
use Log;
use DB;
use Session;
use App\Encounter;

class BillDiscountController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bill_discounts = DB::table('bill_discounts')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);
			return view('bill_discounts.index', [
					'bill_discounts'=>$bill_discounts
			]);
	}

	public function create($encounter_id)
	{

			$encounter = Encounter::find($encounter_id);
			$bill_discount = new BillDiscount();

			$bill_discount->encounter_id = $encounter_id;
			return view('bill_discounts.create', [
					'bill_discount' => $bill_discount,
					'patient'=>$encounter->patient,
				
					]);
	}

	public function store(Request $request) 
	{
			$bill_discount = new BillDiscount();
			$valid = $bill_discount->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bill_discount = new BillDiscount($request->all());
					$bill_discount->discount_id = $request->discount_id;
					$bill_discount->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bill_items/'.$bill_discount->encounter_id);
			} else {
					return redirect('/bill_discounts/create/'.$request->encounter_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bill_discount = BillDiscount::findOrFail($id);
			$encounter = Encounter::find($bill_discount->encounter_id);
			return view('bill_discounts.edit', [
					'bill_discount'=>$bill_discount,
					'patient'=>$encounter->patient,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			if (empty($request->discount_amount)) {
					BillDiscount::find($id)->delete();
					return redirect('/bill_items/'.$request->encounter_id);
			} else {
					$bill_discount = BillDiscount::findOrFail($id);
					$bill_discount->fill($request->input());


					$valid = $bill_discount->validate($request->all(), $request->_method);	

					if ($valid->passes()) {
							$bill_discount->save();
							Session::flash('message', 'Record successfully updated.');
							return redirect('/bill_items/'.$bill_discount->encounter_id);
					} else {
							return redirect('/bill_discounts/'.$request->encounter_id.'/edit')
									->withErrors($valid)
									->withInput();
					}
			}
	}
	
	public function delete($id)
	{
		$bill_discount = BillDiscount::findOrFail($id);
		return view('bill_discounts.destroy', [
			'bill_discount'=>$bill_discount
			]);

	}
	public function destroy($id)
	{	
			BillDiscount::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bill_discounts');
	}
	
	public function search(Request $request)
	{
			$bill_discounts = DB::table('bill_discounts')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('discount_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('bill_discounts.index', [
					'bill_discounts'=>$bill_discounts,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bill_discounts = DB::table('bill_discounts')
					->where('discount_id','=',$id)
					->paginate($this->paginateValue);

			return view('bill_discounts.index', [
					'bill_discounts'=>$bill_discounts
			]);
	}
}
