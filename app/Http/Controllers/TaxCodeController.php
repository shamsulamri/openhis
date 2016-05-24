<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\TaxCode;
use Log;
use DB;
use Session;


class TaxCodeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$tax_codes = DB::table('tax_codes')
					->orderBy('tax_name')
					->paginate($this->paginateValue);
			return view('tax_codes.index', [
					'tax_codes'=>$tax_codes
			]);
	}

	public function create()
	{
			$tax_code = new TaxCode();
			return view('tax_codes.create', [
					'tax_code' => $tax_code,
				
					]);
	}

	public function store(Request $request) 
	{
			$tax_code = new TaxCode();
			$valid = $tax_code->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$tax_code = new TaxCode($request->all());
					$tax_code->tax_code = $request->tax_code;
					$tax_code->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/tax_codes/id/'.$tax_code->tax_code);
			} else {
					return redirect('/tax_codes/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$tax_code = TaxCode::findOrFail($id);
			return view('tax_codes.edit', [
					'tax_code'=>$tax_code,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$tax_code = TaxCode::findOrFail($id);
			$tax_code->fill($request->input());


			$valid = $tax_code->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$tax_code->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/tax_codes/id/'.$id);
			} else {
					return view('tax_codes.edit', [
							'tax_code'=>$tax_code,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$tax_code = TaxCode::findOrFail($id);
		return view('tax_codes.destroy', [
			'tax_code'=>$tax_code
			]);

	}
	public function destroy($id)
	{	
			TaxCode::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/tax_codes');
	}
	
	public function search(Request $request)
	{
			$tax_codes = DB::table('tax_codes')
					->where('tax_name','like','%'.$request->search.'%')
					->orWhere('tax_code', 'like','%'.$request->search.'%')
					->orderBy('tax_name')
					->paginate($this->paginateValue);

			return view('tax_codes.index', [
					'tax_codes'=>$tax_codes,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$tax_codes = DB::table('tax_codes')
					->where('tax_code','=',$id)
					->paginate($this->paginateValue);

			return view('tax_codes.index', [
					'tax_codes'=>$tax_codes
			]);
	}
}
