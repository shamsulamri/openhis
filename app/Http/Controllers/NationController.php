<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Nation;
use Log;
use DB;
use Session;


class NationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$nations = DB::table('ref_nations')
					->orderBy('nation_name')
					->paginate($this->paginateValue);
			return view('nations.index', [
					'nations'=>$nations
			]);
	}

	public function create()
	{
			$nation = new Nation();
			return view('nations.create', [
					'nation' => $nation,
				
					]);
	}

	public function store(Request $request) 
	{
			$nation = new Nation();
			$valid = $nation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$nation = new Nation($request->all());
					$nation->nation_code = $request->nation_code;
					$nation->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/nations/id/'.$nation->nation_code);
			} else {
					return redirect('/nations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$nation = Nation::findOrFail($id);
			return view('nations.edit', [
					'nation'=>$nation,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$nation = Nation::findOrFail($id);
			$nation->fill($request->input());


			$valid = $nation->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$nation->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/nations/id/'.$id);
			} else {
					return view('nations.edit', [
							'nation'=>$nation,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$nation = Nation::findOrFail($id);
		return view('nations.destroy', [
			'nation'=>$nation
			]);

	}
	public function destroy($id)
	{	
			Nation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/nations');
	}
	
	public function search(Request $request)
	{
			$nations = DB::table('ref_nations')
					->where('nation_name','like','%'.$request->search.'%')
					->orWhere('nation_code', 'like','%'.$request->search.'%')
					->orderBy('nation_name')
					->paginate($this->paginateValue);

			return view('nations.index', [
					'nations'=>$nations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$nations = DB::table('ref_nations')
					->where('nation_code','=',$id)
					->paginate($this->paginateValue);

			return view('nations.index', [
					'nations'=>$nations
			]);
	}
}
