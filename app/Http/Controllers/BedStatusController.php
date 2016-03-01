<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BedStatus;
use Log;
use DB;
use Session;


class BedStatusController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bed_statuses = DB::table('bed_statuses')
					->orderBy('status_name')
					->paginate($this->paginateValue);
			return view('bed_statuses.index', [
					'bed_statuses'=>$bed_statuses
			]);
	}

	public function create()
	{
			$bed_status = new BedStatus();
			return view('bed_statuses.create', [
					'bed_status' => $bed_status,
				
					]);
	}

	public function store(Request $request) 
	{
			$bed_status = new BedStatus();
			$valid = $bed_status->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bed_status = new BedStatus($request->all());
					$bed_status->status_code = $request->status_code;
					$bed_status->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bed_statuses/id/'.$bed_status->status_code);
			} else {
					return redirect('/bed_statuses/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bed_status = BedStatus::findOrFail($id);
			return view('bed_statuses.edit', [
					'bed_status'=>$bed_status,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$bed_status = BedStatus::findOrFail($id);
			$bed_status->fill($request->input());

			$bed_status->status_hidden = $request->status_hidden ?: 0;

			$valid = $bed_status->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bed_status->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bed_statuses/id/'.$id);
			} else {
					return view('bed_statuses.edit', [
							'bed_status'=>$bed_status,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bed_status = BedStatus::findOrFail($id);
		return view('bed_statuses.destroy', [
			'bed_status'=>$bed_status
			]);

	}
	public function destroy($id)
	{	
			BedStatus::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bed_statuses');
	}
	
	public function search(Request $request)
	{
			$bed_statuses = DB::table('bed_statuses')
					->where('status_name','like','%'.$request->search.'%')
					->orWhere('status_code', 'like','%'.$request->search.'%')
					->orderBy('status_name')
					->paginate($this->paginateValue);

			return view('bed_statuses.index', [
					'bed_statuses'=>$bed_statuses,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bed_statuses = DB::table('bed_statuses')
					->where('status_code','=',$id)
					->paginate($this->paginateValue);

			return view('bed_statuses.index', [
					'bed_statuses'=>$bed_statuses
			]);
	}
}
