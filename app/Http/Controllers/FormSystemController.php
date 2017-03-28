<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\FormSystem;
use Log;
use DB;
use Session;


class FormSystemController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$form_systems = DB::table('form_systems')
					->orderBy('system_name')
					->paginate($this->paginateValue);
			return view('form_systems.index', [
					'form_systems'=>$form_systems
			]);
	}

	public function create()
	{
			$form_system = new FormSystem();
			return view('form_systems.create', [
					'form_system' => $form_system,
				
					]);
	}

	public function store(Request $request) 
	{
			$form_system = new FormSystem();
			$valid = $form_system->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$form_system = new FormSystem($request->all());
					$form_system->system_code = $request->system_code;
					$form_system->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/form_systems/id/'.$form_system->system_code);
			} else {
					return redirect('/form_systems/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$form_system = FormSystem::findOrFail($id);
			return view('form_systems.edit', [
					'form_system'=>$form_system,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$form_system = FormSystem::findOrFail($id);
			$form_system->fill($request->input());


			$valid = $form_system->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$form_system->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/form_systems/id/'.$id);
			} else {
					return view('form_systems.edit', [
							'form_system'=>$form_system,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$form_system = FormSystem::findOrFail($id);
		return view('form_systems.destroy', [
			'form_system'=>$form_system
			]);

	}
	public function destroy($id)
	{	
			FormSystem::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/form_systems');
	}
	
	public function search(Request $request)
	{
			$form_systems = DB::table('form_systems')
					->where('system_name','like','%'.$request->search.'%')
					->orWhere('system_code', 'like','%'.$request->search.'%')
					->orderBy('system_name')
					->paginate($this->paginateValue);

			return view('form_systems.index', [
					'form_systems'=>$form_systems,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$form_systems = DB::table('form_systems')
					->where('system_code','=',$id)
					->paginate($this->paginateValue);

			return view('form_systems.index', [
					'form_systems'=>$form_systems
			]);
	}
}
