<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugSystem;
use Log;
use DB;
use Session;


class DrugSystemController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_systems = DB::table('drug_systems')
					->orderBy('system_name')
					->paginate($this->paginateValue);
			return view('drug_systems.index', [
					'drug_systems'=>$drug_systems
			]);
	}

	public function create()
	{
			$drug_system = new DrugSystem();
			return view('drug_systems.create', [
					'drug_system' => $drug_system,
				
					]);
	}

	public function store(Request $request) 
	{
			$drug_system = new DrugSystem();
			$valid = $drug_system->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_system = new DrugSystem($request->all());
					$drug_system->system_code = $request->system_code;
					$drug_system->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_systems/id/'.$drug_system->system_code);
			} else {
					return redirect('/drug_systems/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_system = DrugSystem::findOrFail($id);
			return view('drug_systems.edit', [
					'drug_system'=>$drug_system,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_system = DrugSystem::findOrFail($id);
			$drug_system->fill($request->input());


			$valid = $drug_system->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_system->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_systems/id/'.$id);
			} else {
					return view('drug_systems.edit', [
							'drug_system'=>$drug_system,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_system = DrugSystem::findOrFail($id);
		return view('drug_systems.destroy', [
			'drug_system'=>$drug_system
			]);

	}
	public function destroy($id)
	{	
			DrugSystem::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_systems');
	}
	
	public function search(Request $request)
	{
			$drug_systems = DB::table('drug_systems')
					->where('system_name','like','%'.$request->search.'%')
					->orWhere('system_code', 'like','%'.$request->search.'%')
					->orderBy('system_name')
					->paginate($this->paginateValue);

			return view('drug_systems.index', [
					'drug_systems'=>$drug_systems,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_systems = DB::table('drug_systems')
					->where('system_code','=',$id)
					->paginate($this->paginateValue);

			return view('drug_systems.index', [
					'drug_systems'=>$drug_systems
			]);
	}
}
