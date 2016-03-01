<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CareOrganisation;
use Log;
use DB;
use Session;


class CareOrganisationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$care_organisations = DB::table('care_organisations')
					->orderBy('organisation_name')
					->paginate($this->paginateValue);
			return view('care_organisations.index', [
					'care_organisations'=>$care_organisations
			]);
	}

	public function create()
	{
			$care_organisation = new CareOrganisation();
			return view('care_organisations.create', [
					'care_organisation' => $care_organisation,
				
					]);
	}

	public function store(Request $request) 
	{
			$care_organisation = new CareOrganisation();
			$valid = $care_organisation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$care_organisation = new CareOrganisation($request->all());
					$care_organisation->organisation_code = $request->organisation_code;
					$care_organisation->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/care_organisations/id/'.$care_organisation->organisation_code);
			} else {
					return redirect('/care_organisations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$care_organisation = CareOrganisation::findOrFail($id);
			return view('care_organisations.edit', [
					'care_organisation'=>$care_organisation,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$care_organisation = CareOrganisation::findOrFail($id);
			$care_organisation->fill($request->input());


			$valid = $care_organisation->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$care_organisation->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/care_organisations/id/'.$id);
			} else {
					return view('care_organisations.edit', [
							'care_organisation'=>$care_organisation,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$care_organisation = CareOrganisation::findOrFail($id);
		return view('care_organisations.destroy', [
			'care_organisation'=>$care_organisation
			]);

	}
	public function destroy($id)
	{	
			CareOrganisation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/care_organisations');
	}
	
	public function search(Request $request)
	{
			$care_organisations = DB::table('care_organisations')
					->where('organisation_name','like','%'.$request->search.'%')
					->orWhere('organisation_code', 'like','%'.$request->search.'%')
					->orderBy('organisation_name')
					->paginate($this->paginateValue);

			return view('care_organisations.index', [
					'care_organisations'=>$care_organisations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$care_organisations = DB::table('care_organisations')
					->where('organisation_code','=',$id)
					->paginate($this->paginateValue);

			return view('care_organisations.index', [
					'care_organisations'=>$care_organisations
			]);
	}
}
