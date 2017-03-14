<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\FormProperty;
use App\FormPosition;
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

	public function index(Request $request)
	{
			$form_properties = DB::table('form_properties')
					->orderBy('property_name')
					->paginate($this->paginateValue);
			return view('form_properties.index', [
					'form_properties'=>$form_properties,
					'form_code'=>$request->form_code,
			]);
	}

	public function create(Request $request)
	{
			$form_property = new FormProperty();
			return view('form_properties.create', [
					'form_property' => $form_property,
					'form_code'=>$request->form_code,
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

					$form_position = new FormPosition();
					$form_position->form_code = $request->form_code;
					$form_position->property_code = $form_property->property_code;
					$form_position->property_position = 99;
					$form_position->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/form_properties/id/'.$form_property->property_code.'?form_code='.$request->form_code);
			} else {
					return redirect('/form_properties/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function add($form_code, $property_code) 
	{
			$form_position = new FormPosition();
			$form_position->form_code = $form_code;
			$form_position->property_code = $property_code;
			$form_position->property_position = 99;
			$form_position->save();
			Session::flash('message', 'Record successfully created.');
			return redirect('/form_properties?form_code='.$form_code);
	}

	public function edit(Request $request, $id) 
	{
			$form_property = FormProperty::findOrFail($id);
			return view('form_properties.edit', [
					'form_property'=>$form_property,
					'form_code'=>$request->form_code,	
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
					return redirect('/form_properties/id/'.$id.'?form_code='.$request->form_code);
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
			$form = FormProperty::find($id);
			FormProperty::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/form_properties?form_code='.$form->form_code);
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
					'search'=>$request->search,
					'form_code'=>$request->form_code,
					]);
	}

	public function searchById(Request $request, $id)
	{
			$form_properties = DB::table('form_properties')
					->where('property_code','=',$id)
					->paginate($this->paginateValue);

			return view('form_properties.index', [
					'form_properties'=>$form_properties,
					'form_code'=>$request->form_code,
			]);
	}


}
