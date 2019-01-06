<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UserAuthorization;
use Log;
use DB;
use Session;
use App\Store;
use App\QueueLocation;

class UserAuthorizationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$user_authorizations = DB::table('user_authorizations')
					->orderBy('author_name')
					->paginate($this->paginateValue);
			return view('user_authorizations.index', [
					'user_authorizations'=>$user_authorizations
			]);
	}

	public function create()
	{
			$user_authorization = new UserAuthorization();
			return view('user_authorizations.create', [
					'user_authorization' => $user_authorization,
					'location' => QueueLocation::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$user_authorization = new UserAuthorization();
			$valid = $user_authorization->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$user_authorization = new UserAuthorization($request->all());
					$user_authorization->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/user_authorizations');
			} else {
					return redirect('/user_authorizations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$user_authorization = UserAuthorization::findOrFail($id);
			return view('user_authorizations.edit', [
					'user_authorization'=>$user_authorization,
					'location' => QueueLocation::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$user_authorization = UserAuthorization::findOrFail($id);
			$user_authorization->fill($request->input());

			$user_authorization->module_patient = $request->module_patient ?: 0;
			$user_authorization->module_consultation = $request->module_consultation ?: 0;
			$user_authorization->module_inventory = $request->module_inventory ?: 0;
			$user_authorization->module_ward = $request->module_ward ?: 0;
			$user_authorization->module_discharge = $request->module_discharge ?: 0;
			$user_authorization->module_diet = $request->module_diet ?: 0;
			$user_authorization->module_medical_record = $request->module_medical_record ?: 0;
			$user_authorization->module_support = $request->module_support ?: 0;
			$user_authorization->patient_list = $request->patient_list ?: 0;
			$user_authorization->product_list = $request->product_list ?: 0;
			$user_authorization->loan_function = $request->loan_function ?: 0;
			$user_authorization->module_order = $request->module_order ?: 0;
			$user_authorization->product_information_edit = $request->product_information_edit ?: 0;
			$user_authorization->discharge_patient = $request->discharge_patient ?: 0;
			$user_authorization->product_purchase_edit = $request->product_purchase_edit ?: 0;
			$user_authorization->product_sale_edit = $request->product_sale_edit ?: 0;
			$user_authorization->purchase_request = $request->purchase_request ?: 0;
			$user_authorization->appointment_function = $request->appointment_function ?: 0;
			$user_authorization->view_progress_note = $request->view_progress_note ?: 0;

			$valid = $user_authorization->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$user_authorization->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/user_authorizations/id/'.$id);
			} else {
					return view('user_authorizations.edit', [
							'user_authorization'=>$user_authorization,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$user_authorization = UserAuthorization::findOrFail($id);
		return view('user_authorizations.destroy', [
			'user_authorization'=>$user_authorization
			]);

	}
	public function destroy($id)
	{	
			UserAuthorization::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/user_authorizations');
	}
	
	public function search(Request $request)
	{
			$user_authorizations = DB::table('user_authorizations')
					->where('module_consultation','like','%'.$request->search.'%')
					->orderBy('module_consultation')
					->paginate($this->paginateValue);

			return view('user_authorizations.index', [
					'user_authorizations'=>$user_authorizations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$user_authorizations = DB::table('user_authorizations')
					->where('author_id','=',$id)
					->paginate($this->paginateValue);

			return view('user_authorizations.index', [
					'user_authorizations'=>$user_authorizations
			]);
	}
}
