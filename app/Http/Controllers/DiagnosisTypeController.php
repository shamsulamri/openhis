<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DiagnosisType;
use Log;
use DB;
use Session;


class DiagnosisTypeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diagnosis_types = DB::table('ref_diagnosis_types')
					->orderBy('type_name')
					->paginate($this->paginateValue);
			return view('diagnosis_types.index', [
					'diagnosis_types'=>$diagnosis_types
			]);
	}

	public function create()
	{
			$diagnosis_type = new DiagnosisType();
			return view('diagnosis_types.create', [
					'diagnosis_type' => $diagnosis_type,
				
					]);
	}

	public function store(Request $request) 
	{
			$diagnosis_type = new DiagnosisType();
			$valid = $diagnosis_type->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diagnosis_type = new DiagnosisType($request->all());
					$diagnosis_type->type_code = $request->type_code;
					$diagnosis_type->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diagnosis_types/id/'.$diagnosis_type->type_code);
			} else {
					return redirect('/diagnosis_types/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diagnosis_type = DiagnosisType::findOrFail($id);
			return view('diagnosis_types.edit', [
					'diagnosis_type'=>$diagnosis_type,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$diagnosis_type = DiagnosisType::findOrFail($id);
			$diagnosis_type->fill($request->input());


			$valid = $diagnosis_type->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diagnosis_type->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diagnosis_types/id/'.$id);
			} else {
					return view('diagnosis_types.edit', [
							'diagnosis_type'=>$diagnosis_type,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diagnosis_type = DiagnosisType::findOrFail($id);
		return view('diagnosis_types.destroy', [
			'diagnosis_type'=>$diagnosis_type
			]);

	}
	public function destroy($id)
	{	
			DiagnosisType::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diagnosis_types');
	}
	
	public function search(Request $request)
	{
			$diagnosis_types = DB::table('ref_diagnosis_types')
					->where('type_name','like','%'.$request->search.'%')
					->orWhere('type_code', 'like','%'.$request->search.'%')
					->orderBy('type_name')
					->paginate($this->paginateValue);

			return view('diagnosis_types.index', [
					'diagnosis_types'=>$diagnosis_types,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diagnosis_types = DB::table('ref_diagnosis_types')
					->where('type_code','=',$id)
					->paginate($this->paginateValue);

			return view('diagnosis_types.index', [
					'diagnosis_types'=>$diagnosis_types
			]);
	}
}
