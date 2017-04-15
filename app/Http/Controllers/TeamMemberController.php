<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\TeamMember;
use Log;
use DB;
use Session;
use App\Team;


class TeamMemberController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$team_members = DB::table('team_members')
					->orderBy('team_code')
					->paginate($this->paginateValue);
			return view('team_members.index', [
					'team_members'=>$team_members
			]);
	}

	public function create()
	{
			$team_member = new TeamMember();
			return view('team_members.create', [
					'team_member' => $team_member,
					'team' => Team::all()->sortBy('team_name')->lists('team_name', 'team_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$team_member = new TeamMember();
			$valid = $team_member->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$team_member = new TeamMember($request->all());
					$team_member->member_id = $request->member_id;
					$team_member->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/team_members/id/'.$team_member->member_id);
			} else {
					return redirect('/team_members/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$team_member = TeamMember::findOrFail($id);
			return view('team_members.edit', [
					'team_member'=>$team_member,
					'team' => Team::all()->sortBy('team_name')->lists('team_name', 'team_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$team_member = TeamMember::findOrFail($id);
			$team_member->fill($request->input());


			$valid = $team_member->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$team_member->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/team_members/id/'.$id);
			} else {
					return view('team_members.edit', [
							'team_member'=>$team_member,
					'team' => Team::all()->sortBy('team_name')->lists('team_name', 'team_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$team_member = TeamMember::findOrFail($id);
		return view('team_members.destroy', [
			'team_member'=>$team_member
			]);

	}
	public function destroy($id)
	{	
			TeamMember::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/team_members');
	}
	
	public function search(Request $request)
	{
			$team_members = DB::table('team_members')
					->where('team_code','like','%'.$request->search.'%')
					->orWhere('member_id', 'like','%'.$request->search.'%')
					->orderBy('team_code')
					->paginate($this->paginateValue);

			return view('team_members.index', [
					'team_members'=>$team_members,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$team_members = DB::table('team_members')
					->where('member_id','=',$id)
					->paginate($this->paginateValue);

			return view('team_members.index', [
					'team_members'=>$team_members
			]);
	}
}
