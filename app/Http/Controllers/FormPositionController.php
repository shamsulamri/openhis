<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\FormPosition;
use Log;
use DB;
use Session;
use App\Form;
use App\FormProperty as Property;


class FormPositionController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$form_positions = DB::table('form_positions')
					->join('forms', 'form_positions.form_code','=', 'forms.form_code')
					->join('form_properties', 'form_properties.property_code', '=', 'form_positions.property_code')
					->orderBy('form_name')
					->orderBy('property_position')
					->paginate($this->paginateValue);

			return view('form_positions.index', [
					'form_positions'=>$form_positions
			]);
	}

	public function create()
	{
			$form_position = new FormPosition();
			return view('form_positions.create', [
					'form_position' => $form_position,
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'property' => Property::all()->sortBy('property_name')->lists('property_name', 'property_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$form_position = new FormPosition();
			$valid = $form_position->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$form_position = new FormPosition($request->all());
					$form_position->id = $request->id;
					$form_position->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/form_positions/id/'.$form_position->id);
			} else {
					return redirect('/form_positions/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$form_position = FormPosition::findOrFail($id);
			return view('form_positions.edit', [
					'form_position'=>$form_position,
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'property' => Property::all()->sortBy('property_name')->lists('property_name', 'property_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$form_position = FormPosition::findOrFail($id);
			$form_position->fill($request->input());


			$valid = $form_position->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$form_position->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/form_positions/id/'.$id);
			} else {
					return view('form_positions.edit', [
							'form_position'=>$form_position,
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'property' => Property::all()->sortBy('property_name')->lists('property_name', 'property_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$form_position = FormPosition::findOrFail($id);
		return view('form_positions.destroy', [
			'form_position'=>$form_position
			]);

	}
	public function destroy($id)
	{	
			FormPosition::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/form_positions');
	}
	
	public function search(Request $request)
	{
			$form_positions = DB::table('form_positions')
					->join('forms', 'form_positions.form_code','=', 'forms.form_code')
					->join('form_properties', 'form_properties.property_code', '=', 'form_positions.property_code')
					->where('form_positions.property_code','like','%'.$request->search.'%')
					->orWhere('form_positions.form_code', 'like','%'.$request->search.'%')
					->orWhere('form_name', 'like','%'.$request->search.'%')
					->orderBy('form_name')
					->orderBy('property_position')
					->paginate($this->paginateValue);


			return view('form_positions.index', [
					'form_positions'=>$form_positions,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$form_positions = DB::table('form_positions')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('form_positions.index', [
					'form_positions'=>$form_positions
			]);
	}
}
