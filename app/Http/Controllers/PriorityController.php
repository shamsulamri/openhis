<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Priority;
use Log;
use DB;
use Session;


class PriorityController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$priorities = DB::table('ref_priorities')
					->orderBy('priority_name')
					->paginate($this->paginateValue);
			return view('priorities.index', [
					'priorities'=>$priorities
			]);
	}

	public function create()
	{
			$priority = new Priority();
			return view('priorities.create', [
					'priority' => $priority,
				
					]);
	}

	public function store(Request $request) 
	{
			$priority = new Priority();
			$valid = $priority->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$priority = new Priority($request->all());
					$priority->priority_code = $request->priority_code;
					$priority->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/priorities/id/'.$priority->priority_code);
			} else {
					return redirect('/priorities/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$priority = Priority::findOrFail($id);
			return view('priorities.edit', [
					'priority'=>$priority,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$priority = Priority::findOrFail($id);
			$priority->fill($request->input());


			$valid = $priority->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$priority->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/priorities/id/'.$id);
			} else {
					return view('priorities.edit', [
							'priority'=>$priority,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$priority = Priority::findOrFail($id);
		return view('priorities.destroy', [
			'priority'=>$priority
			]);

	}
	public function destroy($id)
	{	
			Priority::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/priorities');
	}
	
	public function search(Request $request)
	{
			$priorities = DB::table('ref_priorities')
					->where('priority_name','like','%'.$request->search.'%')
					->orWhere('priority_code', 'like','%'.$request->search.'%')
					->orderBy('priority_name')
					->paginate($this->paginateValue);

			return view('priorities.index', [
					'priorities'=>$priorities,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$priorities = DB::table('ref_priorities')
					->where('priority_code','=',$id)
					->paginate($this->paginateValue);

			return view('priorities.index', [
					'priorities'=>$priorities
			]);
	}
}
