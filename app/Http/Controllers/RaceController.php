<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Race;
use Log;
use DB;
use Session;
use Gate;

class RaceController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$races = DB::table('ref_races')
					->orderBy('race_name')
					->paginate($this->paginateValue);
			return view('races.index', [
					'races'=>$races
			]);
	}

	public function create()
	{
			$race = new Race();
			return view('races.create', [
					'race' => $race,
				
					]);
	}

	public function store(Request $request) 
	{
			$race = new Race();
			$valid = $race->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$race = new Race($request->all());
					$race->race_code = $request->race_code;
					$race->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/races/id/'.$race->race_code);
			} else {
					return redirect('/races/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$race = Race::findOrFail($id);
			return view('races.edit', [
					'race'=>$race,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$race = Race::findOrFail($id);
			if (Gate::denies('update-race',$race)) {
					return "XCXXX";
			}
			$race->fill($request->input());


			$valid = $race->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$race->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/races/id/'.$id);
			} else {
					return view('races.edit', [
							'race'=>$race,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$race = Race::findOrFail($id);
		return view('races.destroy', [
			'race'=>$race
			]);

	}
	public function destroy($id)
	{	
			Race::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/races');
	}
	
	public function search(Request $request)
	{
			$races = DB::table('ref_races')
					->where('race_name','like','%'.$request->search.'%')
					->orWhere('race_code', 'like','%'.$request->search.'%')
					->orderBy('race_name')
					->paginate($this->paginateValue);

			return view('races.index', [
					'races'=>$races,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$races = DB::table('ref_races')
					->where('race_code','=',$id)
					->paginate($this->paginateValue);

			return view('races.index', [
					'races'=>$races
			]);
	}
}
