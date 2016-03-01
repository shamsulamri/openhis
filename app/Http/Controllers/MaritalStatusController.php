<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MaritalStatus;
use Log;
use DB;
use Session;


class MaritalStatusController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$marital_statuses = DB::table('ref_marital_statuses')
					->orderBy('marital_name')
					->paginate($this->paginateValue);
			return view('marital_statuses.index', [
					'marital_statuses'=>$marital_statuses
			]);
	}

	public function create()
	{
			$marital_status = new MaritalStatus();
			return view('marital_statuses.create', [
					'marital_status' => $marital_status,
				
					]);
	}

	public function store(Request $request) 
	{
			$marital_status = new MaritalStatus();
			$valid = $marital_status->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$marital_status = new MaritalStatus($request->all());
					$marital_status->marital_code = $request->marital_code;
					$marital_status->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/marital_statuses/id/'.$marital_status->marital_code);
			} else {
					return redirect('/marital_statuses/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$marital_status = MaritalStatus::findOrFail($id);
			return view('marital_statuses.edit', [
					'marital_status'=>$marital_status,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$marital_status = MaritalStatus::findOrFail($id);
			$marital_status->fill($request->input());


			$valid = $marital_status->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$marital_status->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/marital_statuses/id/'.$id);
			} else {
					return view('marital_statuses.edit', [
							'marital_status'=>$marital_status,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$marital_status = MaritalStatus::findOrFail($id);
		return view('marital_statuses.destroy', [
			'marital_status'=>$marital_status
			]);

	}
	public function destroy($id)
	{	
			MaritalStatus::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/marital_statuses');
	}
	
	public function search(Request $request)
	{
			$marital_statuses = DB::table('ref_marital_statuses')
					->where('marital_name','like','%'.$request->search.'%')
					->orWhere('marital_code', 'like','%'.$request->search.'%')
					->orderBy('marital_name')
					->paginate($this->paginateValue);

			return view('marital_statuses.index', [
					'marital_statuses'=>$marital_statuses,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$marital_statuses = DB::table('ref_marital_statuses')
					->where('marital_code','=',$id)
					->paginate($this->paginateValue);

			return view('marital_statuses.index', [
					'marital_statuses'=>$marital_statuses
			]);
	}
}
