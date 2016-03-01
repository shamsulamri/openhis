<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CareLevel;
use Log;
use DB;
use Session;


class CareLevelController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$care_levels = DB::table('ref_care_levels')
					->orderBy('care_name')
					->paginate($this->paginateValue);
			return view('care_levels.index', [
					'care_levels'=>$care_levels
			]);
	}

	public function create()
	{
			$care_level = new CareLevel();
			return view('care_levels.create', [
					'care_level' => $care_level,
				
					]);
	}

	public function store(Request $request) 
	{
			$care_level = new CareLevel();
			$valid = $care_level->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$care_level = new CareLevel($request->all());
					$care_level->care_code = $request->care_code;
					$care_level->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/care_levels/id/'.$care_level->care_code);
			} else {
					return redirect('/care_levels/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$care_level = CareLevel::findOrFail($id);
			return view('care_levels.edit', [
					'care_level'=>$care_level,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$care_level = CareLevel::findOrFail($id);
			$care_level->fill($request->input());


			$valid = $care_level->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$care_level->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/care_levels/id/'.$id);
			} else {
					return view('care_levels.edit', [
							'care_level'=>$care_level,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$care_level = CareLevel::findOrFail($id);
		return view('care_levels.destroy', [
			'care_level'=>$care_level
			]);

	}
	public function destroy($id)
	{	
			CareLevel::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/care_levels');
	}
	
	public function search(Request $request)
	{
			$care_levels = DB::table('ref_care_levels')
					->where('care_name','like','%'.$request->search.'%')
					->orWhere('care_code', 'like','%'.$request->search.'%')
					->orderBy('care_name')
					->paginate($this->paginateValue);

			return view('care_levels.index', [
					'care_levels'=>$care_levels,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$care_levels = DB::table('ref_care_levels')
					->where('care_code','=',$id)
					->paginate($this->paginateValue);

			return view('care_levels.index', [
					'care_levels'=>$care_levels
			]);
	}
}
