<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Set;
use Log;
use DB;
use Session;


class SetController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$sets = DB::table('ref_sets')
					->orderBy('set_name')
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
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$set = Set::findOrFail($id);
			$set->fill($request->input());


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
			$sets = DB::table('ref_sets')
					->where('set_name','like','%'.$request->search.'%')
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
			$sets = DB::table('ref_sets')
					->where('set_code','=',$id)
					->paginate($this->paginateValue);

			return view('sets.index', [
					'sets'=>$sets
			]);
	}
}
