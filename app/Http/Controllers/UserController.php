<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Log;
use DB;
use Session;
use App\UserAuthorization;
use App\AppointmentService;
use App\TaxCode;
use Auth;
use Validator;
use App\QueueLocation;


class UserController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
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

			$locations = QueueLocation::select(DB::raw("concat(location_name, ' (', department_name, ')') as location_name, location_code, encounter_code"))
					->leftJoin('departments as b', 'b.department_code', '=', 'queue_locations.department_code')
					->whereNotNull('encounter_code')
					->orderBy('location_name')
					->lists('location_name', 'location_code')
					->prepend('','');

			return view('users.create', [
					'user' => $user,
					'authorizations' => UserAuthorization::all()->sortBy('author_name')->lists('author_name', 'author_id'),
					'services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					'location' => $locations,
					]);
	}

	public function store(Request $request) 
	{
			$user = new User();
			$valid = $user->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$user = new User($request->all());
					$user->id = $request->id;
					$user->password = bcrypt($user->employee_id);
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

			$locations = queuelocation::select(db::raw("concat(location_name, ' (', department_name, ')') as location_name, location_code, encounter_code"))
					->leftjoin('departments as b', 'b.department_code', '=', 'queue_locations.department_code')
					->wherenotnull('encounter_code')
					->orderby('location_name')
					->lists('location_name', 'location_code')
					->prepend('','');

			return view('users.edit', [
					'user'=>$user,
					'authorizations' => UserAuthorization::all()->sortBy('author_name')->lists('author_name', 'author_id'),
					'services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					'location' => $locations,
					]);
	}

	
	public function editProfile() 
	{
			$user = Auth::user();
			return view('users.profile', [
					'user'=>$user,
					'services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					]);
	}

	public function updateProfile(Request $request) 
	{
			$user = User::where('username','=',$request->username)->first();
			$user->fill($request->input());

			$valid = $user->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$user->consultant = $request->consultant ?: 0;
					$user->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/user_profile');
			} else {
					return redirect('/user_profile')
							->withErrors($valid)
							->withInput();
			}
	}

	public function update(Request $request, $id) 
	{
			$user = User::findOrFail($id);
			$user->fill($request->input());

			$valid = $user->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$user->consultant = $request->consultant ?: 0;
					$user->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/users/id/'.$id);
			} else {
					return view('users.edit', [
							'user'=>$user,
							'authorizations' => UserAuthorization::all()->sortBy('author_name')->lists('author_name', 'author_id'),
							'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
							'services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
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
			$users = User::orderBy('name')
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
			$users = User::orderBy('name')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('users.index', [
					'users'=>$users,
			]);
	}

	public function changePassword()
	{
			return view('users.change_password',[
					'user'=>Auth::user(),
			]);
	}

	public function updatePassword(Request $request)
	{
			$rules = array(
				'current_password'	=> 'required',                        
				'new_password'      => ['required',
										'min:6',
										'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/',
										]
										,
				'verify_password' 	=> 'required|same:new_password'           
			);

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) {
					return redirect('/change_password')
							->withErrors($validator)
							->withInput();
			}

			$errors = null;
			$current_password = $request->current_password;
			if (!Hash::check($request->current_password, Auth::user()->password)) {
					$errors['current_password']= "Current password does not match.";
			}

			if (!$errors) {
					$user= Auth::user();
					$user->password = bcrypt($request->new_password);
					$user->save();
					Session::flash('message', 'Password changed successfully.');
					return redirect('/change_password');
			} else {
					return redirect('/change_password')
							->withErrors($errors)
							->withInput();
			}
	}
}
