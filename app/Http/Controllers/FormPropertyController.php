<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\FormProperty;
use Log;
use DB;
use Session;


class FormPropertyController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$form_properties = DB::table('form_properties')
					->orderBy('property_name')
					->paginate($this->paginateValue);
			return view('form_properties.index', [
					'form_properties'=>$form_properties
			]);
	}

	public function create()
	{
			$form_property = new FormProperty();
			return view('form_properties.create', [
					'form_property' => $form_property,
				
					]);
	}

	public function store(Request $request) 
	{
			$form_property = new FormProperty();
			$valid = $form_property->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$form_property = new FormProperty($request->all());
					$form_property->property_code = $request->property_code;
					$form_property->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/form_properties/id/'.$form_property->property_code);
			} else {
					return redirect('/form_properties/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$form_property = FormProperty::findOrFail($id);
			return view('form_properties.edit', [
					'form_property'=>$form_property,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$form_property = FormProperty::findOrFail($id);
			$form_property->fill($request->input());

			$form_property->property_multiline = $request->property_multiline ?: 0;

			$valid = $form_property->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$form_property->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/fmrm_properties/id/'.$id);
			} else {
					return view('form_properties.edit', [
							'form_property'=>$form_property,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$form_property = FormProperty::findOrFail($id);
		return view('form_properties.destroy', [
			'form_property'=>$form_property
			]);

	}
	public function destroy($id)
	{	
			FormProperty::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/form_properties');
	}
	
	public function search(Request $request)
	{
			$form_properties = DB::table('form_properties')
					->where('property_name','like','%'.$request->search.'%')
					->orWhere('property_code', 'like','%'.$request->search.'%')
					->orderBy('property_name')
					->paginate($this->paginateValue);

			return view('form_properties.index', [
					'form_properties'=>$form_properties,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$form_properties = DB::table('form_properties')
					->where('property_code','=',$id)
					->paginate($this->paginateValue);

			return view('form_properties.index', [
					'form_properties'=>$form_properties
			]);
	}
}
