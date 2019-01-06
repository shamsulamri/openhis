<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PurchaseRequestStatus;
use Log;
use DB;
use Session;


class PurchaseRequestStatusController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$purchase_request_statuses = PurchaseRequestStatus::orderBy('status_name')
					->paginate($this->paginateValue);

			return view('purchase_request_statuses.index', [
					'purchase_request_statuses'=>$purchase_request_statuses
			]);
	}

	public function create()
	{
			$purchase_request_status = new PurchaseRequestStatus();
			return view('purchase_request_statuses.create', [
					'purchase_request_status' => $purchase_request_status,
				
					]);
	}

	public function store(Request $request) 
	{
			$purchase_request_status = new PurchaseRequestStatus();
			$valid = $purchase_request_status->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$purchase_request_status = new PurchaseRequestStatus($request->all());
					$purchase_request_status->status_code = $request->status_code;
					$purchase_request_status->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/purchase_request_statuses/id/'.$purchase_request_status->status_code);
			} else {
					return redirect('/purchase_request_statuses/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$purchase_request_status = PurchaseRequestStatus::findOrFail($id);
			return view('purchase_request_statuses.edit', [
					'purchase_request_status'=>$purchase_request_status,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$purchase_request_status = PurchaseRequestStatus::findOrFail($id);
			$purchase_request_status->fill($request->input());


			$valid = $purchase_request_status->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$purchase_request_status->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/purchase_request_statuses/id/'.$id);
			} else {
					return view('purchase_request_statuses.edit', [
							'purchase_request_status'=>$purchase_request_status,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$purchase_request_status = PurchaseRequestStatus::findOrFail($id);
		return view('purchase_request_statuses.destroy', [
			'purchase_request_status'=>$purchase_request_status
			]);

	}
	public function destroy($id)
	{	
			PurchaseRequestStatus::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/purchase_request_statuses');
	}
	
	public function search(Request $request)
	{
			$purchase_request_statuses = DB::table('purchase_request_statuses')
					->where('status_name','like','%'.$request->search.'%')
					->orWhere('status_code', 'like','%'.$request->search.'%')
					->orderBy('status_name')
					->paginate($this->paginateValue);

			return view('purchase_request_statuses.index', [
					'purchase_request_statuses'=>$purchase_request_statuses,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$purchase_request_statuses = DB::table('purchase_request_statuses')
					->where('status_code','=',$id)
					->paginate($this->paginateValue);

			return view('purchase_request_statuses.index', [
					'purchase_request_statuses'=>$purchase_request_statuses
			]);
	}
}
