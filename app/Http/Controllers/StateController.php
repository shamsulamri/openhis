<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\State;
use Log;
use DB;
use Session;

class StateController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$states = DB::table('ref_states')
					->orderBy('state_name')
					->paginate($this->paginateValue);
			return view('states.index', [
					'states'=>$states
			]);
	}

	public function create()
	{
			$state = new State();
			return view('states.create', [
					'state' => $state,
				
					]);
	}

	public function store(Request $request) 
	{
			$state = new State();
			$valid = $state->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$state = new State($request->all());
					$state->state_code = $request->state_code;
					$state->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/states/id/'.$state->state_code);
			} else {
					return redirect('/states/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$state = State::findOrFail($id);
			return view('states.edit', [
					'state'=>$state,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$state = State::findOrFail($id);
			$state->fill($request->input());


			$valid = $state->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$state->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/states/id/'.$id);
			} else {
					return view('states.edit', [
							'state'=>$state,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$state = State::findOrFail($id);
		return view('states.destroy', [
			'state'=>$state
			]);

	}
	public function destroy($id)
	{	
			State::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/states');
	}
	
	public function search(Request $request)
	{
			$states = DB::table('ref_states')
					->where('state_name','like','%'.$request->search.'%')
					->orWhere('state_code', 'like','%'.$request->search.'%')
					->orderBy('state_name')
					->paginate($this->paginateValue);

			return view('states.index', [
					'states'=>$states,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$states = DB::table('ref_states')
					->where('state_code','=',$id)
					->paginate($this->paginateValue);

			return view('states.index', [
					'states'=>$states
			]);
	}
}
