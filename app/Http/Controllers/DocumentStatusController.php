<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DocumentStatus;
use Log;
use DB;
use Session;


class DocumentStatusController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$document_statuses = DB::table('document_statuses')
					->orderBy('status_name')
					->paginate($this->paginateValue);
			return view('document_statuses.index', [
					'document_statuses'=>$document_statuses
			]);
	}

	public function create()
	{
			$document_status = new DocumentStatus();
			return view('document_statuses.create', [
					'document_status' => $document_status,
				
					]);
	}

	public function store(Request $request) 
	{
			$document_status = new DocumentStatus();
			$valid = $document_status->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$document_status = new DocumentStatus($request->all());
					$document_status->status_code = $request->status_code;
					$document_status->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/document_statuses/id/'.$document_status->status_code);
			} else {
					return redirect('/document_statuses/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$document_status = DocumentStatus::findOrFail($id);
			return view('document_statuses.edit', [
					'document_status'=>$document_status,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$document_status = DocumentStatus::findOrFail($id);
			$document_status->fill($request->input());


			$valid = $document_status->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$document_status->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/document_statuses/id/'.$id);
			} else {
					return view('document_statuses.edit', [
							'document_status'=>$document_status,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$document_status = DocumentStatus::findOrFail($id);
		return view('document_statuses.destroy', [
			'document_status'=>$document_status
			]);

	}
	public function destroy($id)
	{	
			DocumentStatus::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/document_statuses');
	}
	
	public function search(Request $request)
	{
			$document_statuses = DB::table('document_statuses')
					->where('status_name','like','%'.$request->search.'%')
					->orWhere('status_code', 'like','%'.$request->search.'%')
					->orderBy('status_name')
					->paginate($this->paginateValue);

			return view('document_statuses.index', [
					'document_statuses'=>$document_statuses,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$document_statuses = DB::table('document_statuses')
					->where('status_code','=',$id)
					->paginate($this->paginateValue);

			return view('document_statuses.index', [
					'document_statuses'=>$document_statuses
			]);
	}
}
