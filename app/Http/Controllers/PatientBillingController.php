<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PatientBilling;
use Log;
use DB;
use Session;
use App\Product;
use App\Tax;


class PatientBillingController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$patient_billings = DB::table('patient_billings')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);
			return view('patient_billings.index', [
					'patient_billings'=>$patient_billings
			]);
	}

	public function create()
	{
			$patient_billing = new PatientBilling();
			return view('patient_billings.create', [
					'patient_billing' => $patient_billing,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'tax' => Tax::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$patient_billing = new PatientBilling();
			$valid = $patient_billing->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$patient_billing = new PatientBilling($request->all());
					$patient_billing->bill_id = $request->bill_id;
					$patient_billing->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/patient_billings/id/'.$patient_billing->bill_id);
			} else {
					return redirect('/patient_billings/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$patient_billing = PatientBilling::findOrFail($id);
			return view('patient_billings.edit', [
					'patient_billing'=>$patient_billing,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'tax' => Tax::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$patient_billing = PatientBilling::findOrFail($id);
			$patient_billing->fill($request->input());

			$patient_billing->bill_exempted = $request->bill_exempted ?: 0;

			$valid = $patient_billing->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$patient_billing->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/patient_billings/id/'.$id);
			} else {
					return view('patient_billings.edit', [
							'patient_billing'=>$patient_billing,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'tax' => Tax::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$patient_billing = PatientBilling::findOrFail($id);
		return view('patient_billings.destroy', [
			'patient_billing'=>$patient_billing
			]);

	}
	public function destroy($id)
	{	
			PatientBilling::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/patient_billings');
	}
	
	public function search(Request $request)
	{
			$patient_billings = DB::table('patient_billings')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('bill_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('patient_billings.index', [
					'patient_billings'=>$patient_billings,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$patient_billings = DB::table('patient_billings')
					->where('bill_id','=',$id)
					->paginate($this->paginateValue);

			return view('patient_billings.index', [
					'patient_billings'=>$patient_billings
			]);
	}
}
