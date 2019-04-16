<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Set;
use Log;
use DB;
use Session;
use App\User;

class SetController extends Controller
{
	public $paginateValue=10;
	public $consultants = null;

	public function __construct()
	{
			$this->middleware('auth');
			$this->consultants = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('module_consultation',1)
							->orderBy('name')
							->lists('name','id')
							->prepend('','');
	}

	public function index()
	{
			$sets = Set::orderBy('set_name')
					->paginate($this->paginateValue);

			return view('sets.index', [
					'sets'=>$sets
			]);
	}

	public function create()
	{
			$set = new Set();
			return view('sets.create', [
					'set' => $set,
					'consultants' => $this->consultants,
				
					]);
	}

	public function show($set_code)
	{
			$set = Set::find($set_code);
			return view('sets.show',[
				'set'=>$set,
				'set_code'=>$set_code,
			]);
	}

	public function store(Request $request) 
	{
			$set = new Set();
			$valid = $set->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$set = new Set($request->all());
					$set->set_code = $request->set_code;
					$set->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/sets/id/'.$set->set_code);
			} else {
					return redirect('/sets/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$set = Set::findOrFail($id);

			return view('sets.edit', [
					'set'=>$set,
					'consultants' => $this->consultants,
					]);
	}

	public function update(Request $request, $id) 
	{
			$set = Set::findOrFail($id);
			$set->fill($request->input());

			$set->set_shortcut = $request->set_shortcut ?: 0;

			$valid = $set->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$set->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/sets/id/'.$id);
			} else {
					return view('sets.edit', [
							'set'=>$set,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$set = Set::findOrFail($id);
		return view('sets.destroy', [
			'set'=>$set
			]);

	}
	public function destroy($id)
	{	
			Set::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/sets');
	}
	
	public function search(Request $request)
	{
			$sets = Set::where('set_name','like','%'.$request->search.'%')
					->orWhere('set_code', 'like','%'.$request->search.'%')
					->orderBy('set_name')
					->paginate($this->paginateValue);

			return view('sets.index', [
					'sets'=>$sets,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$sets = Set::where('set_code','=',$id)
					->paginate($this->paginateValue);

			return view('sets.index', [
					'sets'=>$sets
			]);
	}
}
