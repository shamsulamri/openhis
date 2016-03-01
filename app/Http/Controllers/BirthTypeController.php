<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BirthType;
use Log;
use DB;
use Session;


class BirthTypeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$birth_types = DB::table('ref_birth_types')
					->orderBy('birth_name')
					->paginate($this->paginateValue);
			return view('birth_types.index', [
					'birth_types'=>$birth_types
			]);
	}

	public function create()
	{
			$birth_type = new BirthType();
			return view('birth_types.create', [
					'birth_type' => $birth_type,
				
					]);
	}

	public function store(Request $request) 
	{
			$birth_type = new BirthType();
			$valid = $birth_type->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$birth_type = new BirthType($request->all());
					$birth_type->birth_code = $request->birth_code;
					$birth_type->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/birth_types/id/'.$birth_type->birth_code);
			} else {
					return redirect('/birth_types/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$birth_type = BirthType::findOrFail($id);
			return view('birth_types.edit', [
					'birth_type'=>$birth_type,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$birth_type = BirthType::findOrFail($id);
			$birth_type->fill($request->input());


			$valid = $birth_type->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$birth_type->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/birth_types/id/'.$id);
			} else {
					return view('birth_types.edit', [
							'birth_type'=>$birth_type,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$birth_type = BirthType::findOrFail($id);
		return view('birth_types.destroy', [
			'birth_type'=>$birth_type
			]);

	}
	public function destroy($id)
	{	
			BirthType::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/birth_types');
	}
	
	public function search(Request $request)
	{
			$birth_types = DB::table('ref_birth_types')
					->where('birth_name','like','%'.$request->search.'%')
					->orWhere('birth_code', 'like','%'.$request->search.'%')
					->orderBy('birth_name')
					->paginate($this->paginateValue);

			return view('birth_types.index', [
					'birth_types'=>$birth_types,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$birth_types = DB::table('ref_birth_types')
					->where('birth_code','=',$id)
					->paginate($this->paginateValue);

			return view('birth_types.index', [
					'birth_types'=>$birth_types
			]);
	}
}
