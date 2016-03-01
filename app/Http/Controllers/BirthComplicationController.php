<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BirthComplication;
use Log;
use DB;
use Session;


class BirthComplicationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$birth_complications = DB::table('ref_birth_complications')
					->orderBy('complication_name')
					->paginate($this->paginateValue);
			return view('birth_complications.index', [
					'birth_complications'=>$birth_complications
			]);
	}

	public function create()
	{
			$birth_complication = new BirthComplication();
			return view('birth_complications.create', [
					'birth_complication' => $birth_complication,
				
					]);
	}

	public function store(Request $request) 
	{
			$birth_complication = new BirthComplication();
			$valid = $birth_complication->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$birth_complication = new BirthComplication($request->all());
					$birth_complication->complication_code = $request->complication_code;
					$birth_complication->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/birth_complications/id/'.$birth_complication->complication_code);
			} else {
					return redirect('/birth_complications/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$birth_complication = BirthComplication::findOrFail($id);
			return view('birth_complications.edit', [
					'birth_complication'=>$birth_complication,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$birth_complication = BirthComplication::findOrFail($id);
			$birth_complication->fill($request->input());


			$valid = $birth_complication->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$birth_complication->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/birth_complications/id/'.$id);
			} else {
					return view('birth_complications.edit', [
							'birth_complication'=>$birth_complication,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$birth_complication = BirthComplication::findOrFail($id);
		return view('birth_complications.destroy', [
			'birth_complication'=>$birth_complication
			]);

	}
	public function destroy($id)
	{	
			BirthComplication::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/birth_complications');
	}
	
	public function search(Request $request)
	{
			$birth_complications = DB::table('ref_birth_complications')
					->where('complication_name','like','%'.$request->search.'%')
					->orWhere('complication_code', 'like','%'.$request->search.'%')
					->orderBy('complication_name')
					->paginate($this->paginateValue);

			return view('birth_complications.index', [
					'birth_complications'=>$birth_complications,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$birth_complications = DB::table('ref_birth_complications')
					->where('complication_code','=',$id)
					->paginate($this->paginateValue);

			return view('birth_complications.index', [
					'birth_complications'=>$birth_complications
			]);
	}
}
