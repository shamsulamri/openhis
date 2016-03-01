<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Employer;
use Log;
use DB;
use Session;


class EmployerController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$employers = DB::table('employers')
					->orderBy('employer_name')
					->paginate($this->paginateValue);
			return view('employers.index', [
					'employers'=>$employers
			]);
	}

	public function create()
	{
			$employer = new Employer();
			return view('employers.create', [
					'employer' => $employer,
				
					]);
	}

	public function store(Request $request) 
	{
			$employer = new Employer();
			$valid = $employer->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$employer = new Employer($request->all());
					$employer->employer_code = $request->employer_code;
					$employer->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/employers/id/'.$employer->employer_code);
			} else {
					return redirect('/employers/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$employer = Employer::findOrFail($id);
			return view('employers.edit', [
					'employer'=>$employer,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$employer = Employer::findOrFail($id);
			$employer->fill($request->input());


			$valid = $employer->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$employer->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/employers/id/'.$id);
			} else {
					return view('employers.edit', [
							'employer'=>$employer,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$employer = Employer::findOrFail($id);
		return view('employers.destroy', [
			'employer'=>$employer
			]);

	}
	public function destroy($id)
	{	
			Employer::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/employers');
	}
	
	public function search(Request $request)
	{
			$employers = DB::table('employers')
					->where('employer_name','like','%'.$request->search.'%')
					->orWhere('employer_code', 'like','%'.$request->search.'%')
					->orderBy('employer_name')
					->paginate($this->paginateValue);

			return view('employers.index', [
					'employers'=>$employers,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$employers = DB::table('employers')
					->where('employer_code','=',$id)
					->paginate($this->paginateValue);

			return view('employers.index', [
					'employers'=>$employers
			]);
	}
}
