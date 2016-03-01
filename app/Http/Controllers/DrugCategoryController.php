<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugCategory;
use Log;
use DB;
use Session;
use App\DrugSystem as System;


class DrugCategoryController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_categories = DB::table('drug_categories')
					->orderBy('category_name')
					->paginate($this->paginateValue);
			return view('drug_categories.index', [
					'drug_categories'=>$drug_categories
			]);
	}

	public function create()
	{
			$drug_category = new DrugCategory();
			return view('drug_categories.create', [
					'drug_category' => $drug_category,
					'system' => System::all()->sortBy('system_name')->lists('system_name', 'system_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$drug_category = new DrugCategory();
			$valid = $drug_category->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_category = new DrugCategory($request->all());
					$drug_category->category_code = $request->category_code;
					$drug_category->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_categories/id/'.$drug_category->category_code);
			} else {
					return redirect('/drug_categories/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_category = DrugCategory::findOrFail($id);
			return view('drug_categories.edit', [
					'drug_category'=>$drug_category,
					'system' => System::all()->sortBy('system_name')->lists('system_name', 'system_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_category = DrugCategory::findOrFail($id);
			$drug_category->fill($request->input());


			$valid = $drug_category->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_category->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_categories/id/'.$id);
			} else {
					return view('drug_categories.edit', [
							'drug_category'=>$drug_category,
					'system' => System::all()->sortBy('system_name')->lists('system_name', 'system_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_category = DrugCategory::findOrFail($id);
		return view('drug_categories.destroy', [
			'drug_category'=>$drug_category
			]);

	}
	public function destroy($id)
	{	
			DrugCategory::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_categories');
	}
	
	public function search(Request $request)
	{
			$drug_categories = DB::table('drug_categories')
					->where('category_name','like','%'.$request->search.'%')
					->orWhere('category_code', 'like','%'.$request->search.'%')
					->orderBy('category_name')
					->paginate($this->paginateValue);

			return view('drug_categories.index', [
					'drug_categories'=>$drug_categories,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_categories = DB::table('drug_categories')
					->where('category_code','=',$id)
					->paginate($this->paginateValue);

			return view('drug_categories.index', [
					'drug_categories'=>$drug_categories
			]);
	}
}
