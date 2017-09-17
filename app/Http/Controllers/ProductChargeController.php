<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductCharge;
use Log;
use DB;
use Session;


class ProductChargeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$product_charges = DB::table('product_charges')
					->orderBy('charge_name')
					->paginate($this->paginateValue);
			return view('product_charges.index', [
					'product_charges'=>$product_charges
			]);
	}

	public function create()
	{
			$product_charge = new ProductCharge();
			return view('product_charges.create', [
					'product_charge' => $product_charge,
				
					]);
	}

	public function store(Request $request) 
	{
			$product_charge = new ProductCharge();
			$valid = $product_charge->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product_charge = new ProductCharge($request->all());
					$product_charge->charge_code = $request->charge_code;
					$product_charge->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/product_charges/id/'.$product_charge->charge_code);
			} else {
					return redirect('/product_charges/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$product_charge = ProductCharge::findOrFail($id);
			return view('product_charges.edit', [
					'product_charge'=>$product_charge,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$product_charge = ProductCharge::findOrFail($id);
			$product_charge->fill($request->input());


			$valid = $product_charge->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product_charge->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/product_charges/id/'.$id);
			} else {
					return view('product_charges.edit', [
							'product_charge'=>$product_charge,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$product_charge = ProductCharge::findOrFail($id);
		return view('product_charges.destroy', [
			'product_charge'=>$product_charge
			]);

	}
	public function destroy($id)
	{	
			ProductCharge::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/product_charges');
	}
	
	public function search(Request $request)
	{
			$product_charges = DB::table('product_charges')
					->where('charge_name','like','%'.$request->search.'%')
					->orWhere('charge_code', 'like','%'.$request->search.'%')
					->orderBy('charge_name')
					->paginate($this->paginateValue);

			return view('product_charges.index', [
					'product_charges'=>$product_charges,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$product_charges = DB::table('product_charges')
					->where('charge_code','=',$id)
					->paginate($this->paginateValue);

			return view('product_charges.index', [
					'product_charges'=>$product_charges
			]);
	}
}
