<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AdmissionType;
use Log;
use DB;
use Session;


class AdmissionTypeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$admission_types = DB::table('ref_admissions')
					->orderBy('admission_name')
					->paginate($this->paginateValue);
			return view('admission_types.index', [
					'admission_types'=>$admission_types
			]);
	}

	public function create()
	{
			$admission_type = new AdmissionType();
			return view('admission_types.create', [
					'admission_type' => $admission_type,
				
					]);
	}

	public function store(Request $request) 
	{
			$admission_type = new AdmissionType();
			$valid = $admission_type->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$admission_type = new AdmissionType($request->all());
					$admission_type->admission_code = $request->admission_code;
					$admission_type->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/admission_types/id/'.$admission_type->admission_code);
			} else {
					return redirect('/admission_types/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$admission_type = AdmissionType::findOrFail($id);
			return view('admission_types.edit', [
					'admission_type'=>$admission_type,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$admission_type = AdmissionType::findOrFail($id);
			$admission_type->fill($request->input());


			$valid = $admission_type->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$admission_type->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/admission_types/id/'.$id);
			} else {
					return view('admission_types.edit', [
							'admission_type'=>$admission_type,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$admission_type = AdmissionType::findOrFail($id);
		return view('admission_types.destroy', [
			'admission_type'=>$admission_type
			]);

	}
	public function destroy($id)
	{	
			AdmissionType::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/admission_types');
	}
	
	public function search(Request $request)
	{
			$admission_types = DB::table('ref_admissions')
					->where('admission_name','like','%'.$request->search.'%')
					->orWhere('admission_code', 'like','%'.$request->search.'%')
					->orderBy('admission_name')
					->paginate($this->paginateValue);

			return view('admission_types.index', [
					'admission_types'=>$admission_types,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$admission_types = DB::table('ref_admissions')
					->where('admission_code','=',$id)
					->paginate($this->paginateValue);

			return view('admission_types.index', [
					'admission_types'=>$admission_types
			]);
	}
}
