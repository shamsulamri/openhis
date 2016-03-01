<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Religion;
use Log;
use DB;
use Session;


class ReligionController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$religions = DB::table('ref_religions')
					->orderBy('religion_name')
					->paginate($this->paginateValue);
			return view('religions.index', [
					'religions'=>$religions
			]);
	}

	public function create()
	{
			$religion = new Religion();
			return view('religions.create', [
					'religion' => $religion,
				
					]);
	}

	public function store(Request $request) 
	{
			$religion = new Religion();
			$valid = $religion->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$religion = new Religion($request->all());
					$religion->religion_code = $request->religion_code;
					$religion->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/religions/id/'.$religion->religion_code);
			} else {
					return redirect('/religions/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$religion = Religion::findOrFail($id);
			return view('religions.edit', [
					'religion'=>$religion,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$religion = Religion::findOrFail($id);
			$religion->fill($request->input());


			$valid = $religion->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$religion->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/religions/id/'.$id);
			} else {
					return view('religions.edit', [
							'religion'=>$religion,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$religion = Religion::findOrFail($id);
		return view('religions.destroy', [
			'religion'=>$religion
			]);

	}
	public function destroy($id)
	{	
			Religion::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/religions');
	}
	
	public function search(Request $request)
	{
			$religions = DB::table('ref_religions')
					->where('religion_name','like','%'.$request->search.'%')
					->orWhere('religion_code', 'like','%'.$request->search.'%')
					->orderBy('religion_name')
					->paginate($this->paginateValue);

			return view('religions.index', [
					'religions'=>$religions,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$religions = DB::table('ref_religions')
					->where('religion_code','=',$id)
					->paginate($this->paginateValue);

			return view('religions.index', [
					'religions'=>$religions
			]);
	}
}
