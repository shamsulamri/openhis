<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Registration;
use Log;
use DB;
use Session;


class RegistrationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$registrations = DB::table('ref_registrations')
					->orderBy('registration_name')
					->paginate($this->paginateValue);
			return view('registrations.index', [
					'registrations'=>$registrations
			]);
	}

	public function create()
	{
			$registration = new Registration();
			return view('registrations.create', [
					'registration' => $registration,
					
					]);
	}

	public function store(Request $request) 
	{
			$registration = new Registration();
			$valid = $registration->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$registration = new Registration($request->all());
					$registration->registration_code = $request->registration_code;
					$registration->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/registrations/id/'.$registration->registration_code);
			} else {
					return redirect('/registrations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$registration = Registration::findOrFail($id);
			return view('registrations.edit', [
					'registration'=>$registration,
					
					]);
	}

	public function update(Request $request, $id) 
	{
			$registration = Registration::findOrFail($id);
			$registration->fill($request->input());
			$valid = $registration->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$registration->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/registrations/id/'.$id);
			} else {
					return view('registrations.edit', [
							'registration'=>$registration
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$registration = Registration::findOrFail($id);
		return view('registrations.destroy', [
			'registration'=>$registration
			]);

	}
	public function destroy($id)
	{	
			Registration::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/registrations');
	}
	
	public function search(Request $request)
	{
			$registrations = DB::table('ref_registrations')
					->where('registration_name','like','%'.$request->search.'%')
					->orWhere('registration_code', 'like','%'.$request->search.'%')
					->orderBy('registration_name')
					->paginate($this->paginateValue);

			return view('registrations.index', [
					'registrations'=>$registrations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$registrations = DB::table('ref_registrations')
					->where('registration_code','=',$id)
					->paginate($this->paginateValue);

			return view('registrations.index', [
					'registrations'=>$registrations
			]);
	}
}
