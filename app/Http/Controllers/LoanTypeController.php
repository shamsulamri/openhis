<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\LoanType;
use Log;
use DB;
use Session;


class LoanTypeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$loan_types = DB::table('loan_types')
					->orderBy('type_name')
					->paginate($this->paginateValue);
			return view('loan_types.index', [
					'loan_types'=>$loan_types
			]);
	}

	public function create()
	{
			$loan_type = new LoanType();
			return view('loan_types.create', [
					'loan_type' => $loan_type,
				
					]);
	}

	public function store(Request $request) 
	{
			$loan_type = new LoanType();
			$valid = $loan_type->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$loan_type = new LoanType($request->all());
					$loan_type->type_code = $request->type_code;
					$loan_type->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/loan_types/id/'.$loan_type->type_code);
			} else {
					return redirect('/loan_types/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$loan_type = LoanType::findOrFail($id);
			return view('loan_types.edit', [
					'loan_type'=>$loan_type,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$loan_type = LoanType::findOrFail($id);
			$loan_type->fill($request->input());


			$valid = $loan_type->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$loan_type->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/loan_types/id/'.$id);
			} else {
					return view('loan_types.edit', [
							'loan_type'=>$loan_type,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$loan_type = LoanType::findOrFail($id);
		return view('loan_types.destroy', [
			'loan_type'=>$loan_type
			]);

	}
	public function destroy($id)
	{	
			LoanType::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/loan_types');
	}
	
	public function search(Request $request)
	{
			$loan_types = DB::table('loan_types')
					->where('type_name','like','%'.$request->search.'%')
					->orWhere('type_code', 'like','%'.$request->search.'%')
					->orderBy('type_name')
					->paginate($this->paginateValue);

			return view('loan_types.index', [
					'loan_types'=>$loan_types,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$loan_types = DB::table('loan_types')
					->where('type_code','=',$id)
					->paginate($this->paginateValue);

			return view('loan_types.index', [
					'loan_types'=>$loan_types
			]);
	}
}
