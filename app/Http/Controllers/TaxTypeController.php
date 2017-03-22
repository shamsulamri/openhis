<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\TaxType;
use Log;
use DB;
use Session;


class TaxTypeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$tax_types = DB::table('tax_types')
					->orderBy('type_name')
					->paginate($this->paginateValue);
			return view('tax_types.index', [
					'tax_types'=>$tax_types
			]);
	}

	public function create()
	{
			$tax_type = new TaxType();
			return view('tax_types.create', [
					'tax_type' => $tax_type,
				
					]);
	}

	public function store(Request $request) 
	{
			$tax_type = new TaxType();
			$valid = $tax_type->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$tax_type = new TaxType($request->all());
					$tax_type->type_code = $request->type_code;
					$tax_type->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/tax_types/id/'.$tax_type->type_code);
			} else {
					return redirect('/tax_types/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$tax_type = TaxType::findOrFail($id);
			return view('tax_types.edit', [
					'tax_type'=>$tax_type,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$tax_type = TaxType::findOrFail($id);
			$tax_type->fill($request->input());


			$valid = $tax_type->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$tax_type->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/tax_types/id/'.$id);
			} else {
					return view('tax_types.edit', [
							'tax_type'=>$tax_type,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$tax_type = TaxType::findOrFail($id);
		return view('tax_types.destroy', [
			'tax_type'=>$tax_type
			]);

	}
	public function destroy($id)
	{	
			TaxType::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/tax_types');
	}
	
	public function search(Request $request)
	{
			$tax_types = DB::table('tax_types')
					->where('type_name','like','%'.$request->search.'%')
					->orWhere('type_code', 'like','%'.$request->search.'%')
					->orderBy('type_name')
					->paginate($this->paginateValue);

			return view('tax_types.index', [
					'tax_types'=>$tax_types,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$tax_types = DB::table('tax_types')
					->where('type_code','=',$id)
					->paginate($this->paginateValue);

			return view('tax_types.index', [
					'tax_types'=>$tax_types
			]);
	}
}
