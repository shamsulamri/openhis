<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BedCharge;
use Log;
use DB;
use Session;


class BedChargeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bed_charges = DB::table('bed_charges')
					->orderBy('bed_code')
					->paginate($this->paginateValue);
			return view('bed_charges.index', [
					'bed_charges'=>$bed_charges
			]);
	}

	public function create()
	{
			$bed_charge = new BedCharge();
			return view('bed_charges.create', [
					'bed_charge' => $bed_charge,
				
					]);
	}

	public function store(Request $request) 
	{
			$bed_charge = new BedCharge();
			$valid = $bed_charge->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bed_charge = new BedCharge($request->all());
					$bed_charge->charge_id = $request->charge_id;
					$bed_charge->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bed_charges/id/'.$bed_charge->charge_id);
			} else {
					return redirect('/bed_charges/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bed_charge = BedCharge::findOrFail($id);
			return view('bed_charges.edit', [
					'bed_charge'=>$bed_charge,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$bed_charge = BedCharge::findOrFail($id);
			$bed_charge->fill($request->input());


			$valid = $bed_charge->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bed_charge->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bed_charges/id/'.$id);
			} else {
					return view('bed_charges.edit', [
							'bed_charge'=>$bed_charge,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bed_charge = BedCharge::findOrFail($id);
		return view('bed_charges.destroy', [
			'bed_charge'=>$bed_charge
			]);

	}
	public function destroy($id)
	{	
			BedCharge::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bed_charges');
	}
	
	public function search(Request $request)
	{
			$bed_charges = DB::table('bed_charges')
					->where('bed_code','like','%'.$request->search.'%')
					->orWhere('charge_id', 'like','%'.$request->search.'%')
					->orderBy('bed_code')
					->paginate($this->paginateValue);

			return view('bed_charges.index', [
					'bed_charges'=>$bed_charges,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bed_charges = DB::table('bed_charges')
					->where('charge_id','=',$id)
					->paginate($this->paginateValue);

			return view('bed_charges.index', [
					'bed_charges'=>$bed_charges
			]);
	}
}
