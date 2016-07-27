<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Log;
use DB;
use Session;
use App\UserAuthorization;

class UserController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$users = DB::table('users') 
					->orderBy('name')
					->paginate($this->paginateValue);


			$users = User::whereNotNull('name')
					->orderBy('name')
					->paginate($this->paginateValue);

			return view('users.index', [
					'users'=>$users,
			]);
	}

	public function create()
	{
			$user = new User();
			return view('users.create', [
					'user' => $user,
				
					]);
	}

	public function store(Request $request) 
	{
			$user = new User();
			$valid = $user->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$user = new User($request->all());
					$user->id = $request->id;
					$user->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/users/id/'.$user->id);
			} else {
					return redirect('/users/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$user = User::findOrFail($id);
			return view('users.edit', [
					'user'=>$user,
					'authorizations' => UserAuthorization::all()->sortBy('author_name')->lists('author_name', 'author_id'),
					]);
	}

	public function update(Request $request, $id) 
	{
			$user = User::findOrFail($id);
			$user->fill($request->input());


			$valid = $user->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$user->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/users/id/'.$id);
			} else {
					return view('users.edit', [
							'user'=>$user,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$user = User::findOrFail($id);
		return view('users.destroy', [
			'user'=>$user
			]);

	}
	public function destroy($id)
	{	
			User::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/users');
	}
	
	public function search(Request $request)
	{
			$users = DB::table('users')
					->where('name','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
					->orderBy('name')
					->paginate($this->paginateValue);

			return view('users.index', [
					'users'=>$users,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$users = DB::table('users')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('users.index', [
					'users'=>$users
			]);
	}
}
