<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Gender;
use Log;
use DB;
use Session;


class GenderController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$genders = DB::table('ref_genders')
					->orderBy('gender_name')
					->paginate($this->paginateValue);
			return view('genders.index', [
					'genders'=>$genders
			]);
	}

	public function create()
	{
			$gender = new Gender();
			return view('genders.create', [
					'gender' => $gender,
				
					]);
	}

	public function store(Request $request) 
	{
			$gender = new Gender();
			$valid = $gender->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$gender = new Gender($request->all());
					$gender->gender_code = $request->gender_code;
					$gender->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/genders/id/'.$gender->gender_code);
			} else {
					return redirect('/genders/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$gender = Gender::findOrFail($id);
			return view('genders.edit', [
					'gender'=>$gender,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$gender = Gender::findOrFail($id);
			$gender->fill($request->input());


			$valid = $gender->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$gender->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/genders/id/'.$id);
			} else {
					return view('genders.edit', [
							'gender'=>$gender,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$gender = Gender::findOrFail($id);
		return view('genders.destroy', [
			'gender'=>$gender
			]);

	}
	public function destroy($id)
	{	
			Gender::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/genders');
	}
	
	public function search(Request $request)
	{
			$genders = DB::table('ref_genders')
					->where('gender_name','like','%'.$request->search.'%')
					->orWhere('gender_code', 'like','%'.$request->search.'%')
					->orderBy('gender_name')
					->paginate($this->paginateValue);

			return view('genders.index', [
					'genders'=>$genders,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$genders = DB::table('ref_genders')
					->where('gender_code','=',$id)
					->paginate($this->paginateValue);

			return view('genders.index', [
					'genders'=>$genders
			]);
	}
}
