<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Employee;
use App\User;
use App\UserAuthorization;
use Log;
use DB;
use Session;


class EmployeeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$employees = DB::connection('mysql2')->table('siso_emp_his')
					->orderBy('name')
					->paginate($this->paginateValue);
			return view('employees.index', [
					'employees'=>$employees
			]);
	}

	public function create()
	{
			$employee = new Employee();
			return view('employees.create', [
					'employee' => $employee,
				
					]);
	}

	public function store(Request $request) 
	{
			$employee = new Employee();
			$valid = $employee->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$employee = new Employee($request->all());
					$employee->empid = $request->empid;
					$employee->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/employees/id/'.$employee->empid);
			} else {
					return redirect('/employees/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$employee = Employee::findOrFail($id);
			return view('employees.edit', [
					'employee'=>$employee,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$employee = Employee::findOrFail($id);
			$employee->fill($request->input());


			$valid = $employee->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$employee->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/employees/id/'.$id);
			} else {
					return view('employees.edit', [
							'employee'=>$employee,
				
							])
							->withErrors($valid);			
			}
	}
	
	/**
	public function delete($id)
	{
		$employee = Employee::findOrFail($id);
		return view('employees.destroy', [
			'employee'=>$employee
			]);

	}
	
	public function destroy($id)
	{	
			Employee::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/employees');
	}
	**/
	
	public function search(Request $request)
	{
			$employees = DB::connection('mysql2')->table('siso_emp_his')
					->where('name','like','%'.$request->search.'%')
					->orWhere('empid', 'like','%'.$request->search.'%')
					->orderBy('name')
					->paginate($this->paginateValue);

			return view('employees.index', [
					'employees'=>$employees,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$employees = DB::conneciton('mysql2')->table('siso_emp_his')
					->where('empid','=',$id)
					->paginate($this->paginateValue);

			return view('employees.index', [
					'employees'=>$employees
			]);
	}

	public function createUser($id)
	{
			$employee = Employee::findOrFail($id);
			$user = new User();
			$user->name = $employee->name;
			$user->username = $employee->nickname;
			$user->email = $employee->email;
			$user->employee_id = $employee->empid;

			return view('users.create', [
					'user' => $user,
					'authorizations' => UserAuthorization::all()->sortBy('author_name')->lists('author_name', 'author_id'),
					]);
	}
}
