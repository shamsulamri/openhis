<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Department;
use Log;
use DB;
use Session;


class DepartmentController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$departments = DB::table('departments')
					->orderBy('department_name')
					->paginate($this->paginateValue);
			return view('departments.index', [
					'departments'=>$departments
			]);
	}

	public function create()
	{
			$department = new Department();
			return view('departments.create', [
					'department' => $department,
				
					]);
	}

	public function store(Request $request) 
	{
			$department = new Department();
			$valid = $department->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$department = new Department($request->all());
					$department->department_code = $request->department_code;
					$department->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/departments/id/'.$department->department_code);
			} else {
					return redirect('/departments/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$department = Department::findOrFail($id);
			return view('departments.edit', [
					'department'=>$department,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$department = Department::findOrFail($id);
			$department->fill($request->input());


			$valid = $department->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$department->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/departments/id/'.$id);
			} else {
					return view('departments.edit', [
							'department'=>$department,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$department = Department::findOrFail($id);
		return view('departments.destroy', [
			'department'=>$department
			]);

	}
	public function destroy($id)
	{	
			Department::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/departments');
	}
	
	public function search(Request $request)
	{
			$departments = DB::table('departments')
					->where('department_name','like','%'.$request->search.'%')
					->orWhere('department_code', 'like','%'.$request->search.'%')
					->orderBy('department_name')
					->paginate($this->paginateValue);

			return view('departments.index', [
					'departments'=>$departments,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$departments = DB::table('departments')
					->where('department_code','=',$id)
					->paginate($this->paginateValue);

			return view('departments.index', [
					'departments'=>$departments
			]);
	}
}
