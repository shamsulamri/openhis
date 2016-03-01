<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Ward;
use Log;
use DB;
use Session;
use App\Gender;
use App\Department;


class WardController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$wards = DB::table('wards')
					->orderBy('ward_name')
					->paginate($this->paginateValue);
			return view('wards.index', [
					'wards'=>$wards
			]);
	}

	public function create()
	{
			$ward = new Ward();
			return view('wards.create', [
					'ward' => $ward,
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$ward = new Ward();
			$valid = $ward->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$ward = new Ward($request->all());
					$ward->ward_code = $request->ward_code;
					$ward->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/wards/id/'.$ward->ward_code);
			} else {
					return redirect('/wards/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$ward = Ward::findOrFail($id);
			return view('wards.edit', [
					'ward'=>$ward,
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$ward = Ward::findOrFail($id);
			$ward->fill($request->input());


			$valid = $ward->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$ward->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/wards/id/'.$id);
			} else {
					return view('wards.edit', [
							'ward'=>$ward,
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$ward = Ward::findOrFail($id);
		return view('wards.destroy', [
			'ward'=>$ward
			]);

	}
	public function destroy($id)
	{	
			Ward::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/wards');
	}
	
	public function search(Request $request)
	{
			$wards = DB::table('wards')
					->where('ward_name','like','%'.$request->search.'%')
					->orWhere('ward_code', 'like','%'.$request->search.'%')
					->orderBy('ward_name')
					->paginate($this->paginateValue);

			return view('wards.index', [
					'wards'=>$wards,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$wards = DB::table('wards')
					->where('ward_code','=',$id)
					->paginate($this->paginateValue);

			return view('wards.index', [
					'wards'=>$wards
			]);
	}
}
