<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Drug;
use Log;
use DB;
use Session;
use App\DrugCategory as Category;


class DrugController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drugs = DB::table('drugs')
					->orderBy('drug_generic_name')
					->paginate($this->paginateValue);
			return view('drugs.index', [
					'drugs'=>$drugs
			]);
	}

	public function create()
	{
			$drug = new Drug();
			return view('drugs.create', [
					'drug' => $drug,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$drug = new Drug();
			$valid = $drug->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug = new Drug($request->all());
					$drug->drug_code = $request->drug_code;
					$drug->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drugs/id/'.$drug->drug_code);
			} else {
					return redirect('/drugs/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug = Drug::findOrFail($id);
			return view('drugs.edit', [
					'drug'=>$drug,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug = Drug::findOrFail($id);
			$drug->fill($request->input());

			$drug->drug_unit_charge = $request->drug_unit_charge ?: 0;

			$valid = $drug->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drugs/id/'.$id);
			} else {
					return view('drugs.edit', [
							'drug'=>$drug,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug = Drug::findOrFail($id);
		return view('drugs.destroy', [
			'drug'=>$drug
			]);

	}
	public function destroy($id)
	{	
			Drug::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drugs');
	}
	
	public function search(Request $request)
	{
			$drugs = DB::table('drugs')
					->where('drug_trade_name','like','%'.$request->search.'%')
					->orWhere('drug_code', 'like','%'.$request->search.'%')
					->orderBy('drug_trade_name')
					->paginate($this->paginateValue);

			return view('drugs.index', [
					'drugs'=>$drugs,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drugs = DB::table('drugs')
					->where('drug_code','=',$id)
					->paginate($this->paginateValue);

			return view('drugs.index', [
					'drugs'=>$drugs
			]);
	}
}
