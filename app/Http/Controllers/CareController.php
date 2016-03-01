<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Care;
use Log;
use DB;
use Session;


class CareController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$cares = DB::table('ref_care_levels')
					->orderBy('care_name')
					->paginate($this->paginateValue);
			return view('cares.index', [
					'cares'=>$cares
			]);
	}

	public function create()
	{
			$care = new Care();
			return view('cares.create', [
					'care' => $care,
					
					]);
	}

	public function store(Request $request) 
	{
			$care = new Care();
			$valid = $care->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$care = new Care($request->all());
					$care->care_code = $request->care_code;
					$care->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/cares/id/'.$care->care_code);
			} else {
					return redirect('/cares/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$care = Care::findOrFail($id);
			return view('cares.edit', [
					'care'=>$care,
					
					]);
	}

	public function update(Request $request, $id) 
	{
			$care = Care::findOrFail($id);
			$care->fill($request->input());
			$valid = $care->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$care->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/cares/id/'.$id);
			} else {
					return view('cares.edit', [
							'care'=>$care
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$care = Care::findOrFail($id);
		return view('cares.destroy', [
			'care'=>$care
			]);

	}
	public function destroy($id)
	{	
			Care::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/cares');
	}
	
	public function search(Request $request)
	{
			$cares = DB::table('ref_care_levels')
					->where('care_name','like','%'.$request->search.'%')
					->orWhere('care_code', 'like','%'.$request->search.'%')
					->orderBy('care_name')
					->paginate($this->paginateValue);

			return view('cares.index', [
					'cares'=>$cares,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$cares = DB::table('ref_care_levels')
					->where('care_code','=',$id)
					->paginate($this->paginateValue);

			return view('cares.index', [
					'cares'=>$cares
			]);
	}
}
