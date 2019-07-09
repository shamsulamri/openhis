<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Form;
use Log;
use DB;
use Session;
use App\FormPosition;
use App\Consultation;

class FormController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$forms = DB::table('forms')
					->orderBy('form_name')
					->paginate($this->paginateValue);
			return view('forms.index', [
					'forms'=>$forms
			]);
	}

	public function create()
	{
			$form = new Form();
			return view('forms.create', [
					'form' => $form,
				
					]);
	}

	public function show($id)
	{
			$form = Form::findOrFail($id);
			return view('forms.show', [
					'form'=>$form,
				
					]);
	}

	public function store(Request $request) 
	{
			$form = new Form();
			$valid = $form->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$form = new Form($request->all());
					$form->form_code = $request->form_code;
					$form->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/forms/id/'.$form->form_code);
			} else {
					return redirect('/forms/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$form = Form::findOrFail($id);
			return view('forms.edit', [
					'form'=>$form,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$form = Form::findOrFail($id);
			$form->fill($request->input());


			$valid = $form->validate($request->all(), $request->_method);	

			$form->form_visible = $request->form_visible ?: 0;
			$form->form_has_graph = $request->form_has_graph ?: 0;

			if ($valid->passes()) {
					$form->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/forms');
			} else {
					return view('forms.edit', [
							'form'=>$form,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$form = Form::findOrFail($id);
		return view('forms.destroy', [
			'form'=>$form
			]);

	}
	public function destroy($id)
	{	
			Form::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/forms');
	}
	
	public function search(Request $request)
	{
			$forms = DB::table('forms')
					->where('form_name','like','%'.$request->search.'%')
					->orWhere('form_code', 'like','%'.$request->search.'%')
					->orderBy('form_name')
					->paginate($this->paginateValue);

			return view('forms.index', [
					'forms'=>$forms,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$forms = DB::table('forms')
					->where('form_code','=',$id)
					->paginate($this->paginateValue);

			return view('forms.index', [
					'forms'=>$forms
			]);
	}

	public function table($form_code, $encounter_id)
	{
			$id = Session::get('consultation_id');
			$consultation = Consultation::where('consultation_id', $id)
					->where('consultation_status', 1)
					->first();
			$encounter = $consultation->encounter;
			$properties = FormPosition::where('form_code', '=', $form_code)
					->where('property_code','<>','header')
					->orderBy('property_position')
					->get();
			$form = Form::find($form_code);

			return view('forms.table', [
					'properties'=>$properties,
					'form'=>$form,
					'consultation'=>$consultation,
					'patient'=>$encounter->patient,
					'slot'=>60,

			]);
	}
}
