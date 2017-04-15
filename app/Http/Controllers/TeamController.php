<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Team;
use Log;
use DB;
use Session;


class TeamController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$teams = DB::table('teams')
					->orderBy('team_name')
					->paginate($this->paginateValue);
			return view('teams.index', [
					'teams'=>$teams
			]);
	}

	public function create()
	{
			$team = new Team();
			return view('teams.create', [
					'team' => $team,
				
					]);
	}

	public function store(Request $request) 
	{
			$team = new Team();
			$valid = $team->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$team = new Team($request->all());
					$team->team_code = $request->team_code;
					$team->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/teams/id/'.$team->team_code);
			} else {
					return redirect('/teams/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$team = Team::findOrFail($id);
			return view('teams.edit', [
					'team'=>$team,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$team = Team::findOrFail($id);
			$team->fill($request->input());


			$valid = $team->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$team->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/teams/id/'.$id);
			} else {
					return view('teams.edit', [
							'team'=>$team,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$team = Team::findOrFail($id);
		return view('teams.destroy', [
			'team'=>$team
			]);

	}
	public function destroy($id)
	{	
			Team::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/teams');
	}
	
	public function search(Request $request)
	{
			$teams = DB::table('teams')
					->where('team_name','like','%'.$request->search.'%')
					->orWhere('team_code', 'like','%'.$request->search.'%')
					->orderBy('team_name')
					->paginate($this->paginateValue);

			return view('teams.index', [
					'teams'=>$teams,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$teams = DB::table('teams')
					->where('team_code','=',$id)
					->paginate($this->paginateValue);

			return view('teams.index', [
					'teams'=>$teams
			]);
	}
}
