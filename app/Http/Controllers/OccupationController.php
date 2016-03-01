<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Occupation;
use Log;
use DB;
use Session;


class OccupationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$occupations = DB::table('ref_occupations')
					->orderBy('occupation_name')
					->paginate($this->paginateValue);
			return view('occupations.index', [
					'occupations'=>$occupations
			]);
	}

	public function create()
	{
			$occupation = new Occupation();
			return view('occupations.create', [
					'occupation' => $occupation,
				
					]);
	}

	public function store(Request $request) 
	{
			$occupation = new Occupation();
			$valid = $occupation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$occupation = new Occupation($request->all());
					$occupation->occupation_code = $request->occupation_code;
					$occupation->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/occupations/id/'.$occupation->occupation_code);
			} else {
					return redirect('/occupations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$occupation = Occupation::findOrFail($id);
			return view('occupations.edit', [
					'occupation'=>$occupation,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$occupation = Occupation::findOrFail($id);
			$occupation->fill($request->input());


			$valid = $occupation->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$occupation->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/occupations/id/'.$id);
			} else {
					return view('occupations.edit', [
							'occupation'=>$occupation,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$occupation = Occupation::findOrFail($id);
		return view('occupations.destroy', [
			'occupation'=>$occupation
			]);

	}
	public function destroy($id)
	{	
			Occupation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/occupations');
	}
	
	public function search(Request $request)
	{
			$occupations = DB::table('ref_occupations')
					->where('occupation_name','like','%'.$request->search.'%')
					->orWhere('occupation_code', 'like','%'.$request->search.'%')
					->orderBy('occupation_name')
					->paginate($this->paginateValue);

			return view('occupations.index', [
					'occupations'=>$occupations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$occupations = DB::table('ref_occupations')
					->where('occupation_code','=',$id)
					->paginate($this->paginateValue);

			return view('occupations.index', [
					'occupations'=>$occupations
			]);
	}
}
