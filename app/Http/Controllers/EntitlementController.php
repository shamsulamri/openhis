<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Entitlement;
use Log;
use DB;
use Session;


class EntitlementController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$entitlements = DB::table('ref_entitlements')
					->orderBy('entitlement_code')
					->paginate($this->paginateValue);
			return view('entitlements.index', [
					'entitlements'=>$entitlements
			]);
	}

	public function create()
	{
			$entitlement = new Entitlement();
			return view('entitlements.create', [
					'entitlement' => $entitlement,
				
					]);
	}

	public function store(Request $request) 
	{
			$entitlement = new Entitlement();
			$valid = $entitlement->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$entitlement = new Entitlement($request->all());
					$entitlement->entitlement_code = $request->entitlement_code;
					$entitlement->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/entitlements/id/'.$entitlement->entitlement_code);
			} else {
					return redirect('/entitlements/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$entitlement = Entitlement::findOrFail($id);
			return view('entitlements.edit', [
					'entitlement'=>$entitlement,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$entitlement = Entitlement::findOrFail($id);
			$entitlement->fill($request->input());


			$valid = $entitlement->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$entitlement->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/entitlements/id/'.$id);
			} else {
					return view('entitlements.edit', [
							'entitlement'=>$entitlement,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$entitlement = Entitlement::findOrFail($id);
		return view('entitlements.destroy', [
			'entitlement'=>$entitlement
			]);

	}
	public function destroy($id)
	{	
			Entitlement::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/entitlements');
	}
	
	public function search(Request $request)
	{
			$entitlements = DB::table('ref_entitlements')
					->where('entitlement_code','like','%'.$request->search.'%')
					->orWhere('entitlement_code', 'like','%'.$request->search.'%')
					->orderBy('entitlement_code')
					->paginate($this->paginateValue);

			return view('entitlements.index', [
					'entitlements'=>$entitlements,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$entitlements = DB::table('ref_entitlements')
					->where('entitlement_code','=',$id)
					->paginate($this->paginateValue);

			return view('entitlements.index', [
					'entitlements'=>$entitlements
			]);
	}
}
