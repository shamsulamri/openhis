<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MaintenanceReason;
use Log;
use DB;
use Session;


class MaintenanceReasonController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$maintenance_reasons = DB::table('ref_maintenance_reasons')
					->orderBy('reason_code')
					->paginate($this->paginateValue);
			return view('maintenance_reasons.index', [
					'maintenance_reasons'=>$maintenance_reasons
			]);
	}

	public function create()
	{
			$maintenance_reason = new MaintenanceReason();
			return view('maintenance_reasons.create', [
					'maintenance_reason' => $maintenance_reason,
				
					]);
	}

	public function store(Request $request) 
	{
			$maintenance_reason = new MaintenanceReason();
			$valid = $maintenance_reason->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$maintenance_reason = new MaintenanceReason($request->all());
					$maintenance_reason->reason_code = $request->reason_code;
					$maintenance_reason->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/maintenance_reasons/id/'.$maintenance_reason->reason_code);
			} else {
					return redirect('/maintenance_reasons/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$maintenance_reason = MaintenanceReason::findOrFail($id);
			return view('maintenance_reasons.edit', [
					'maintenance_reason'=>$maintenance_reason,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$maintenance_reason = MaintenanceReason::findOrFail($id);
			$maintenance_reason->fill($request->input());


			$valid = $maintenance_reason->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$maintenance_reason->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/maintenance_reasons/id/'.$id);
			} else {
					return view('maintenance_reasons.edit', [
							'maintenance_reason'=>$maintenance_reason,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$maintenance_reason = MaintenanceReason::findOrFail($id);
		return view('maintenance_reasons.destroy', [
			'maintenance_reason'=>$maintenance_reason
			]);

	}
	public function destroy($id)
	{	
			MaintenanceReason::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/maintenance_reasons');
	}
	
	public function search(Request $request)
	{
			$maintenance_reasons = DB::table('ref_maintenance_reasons')
					->where('reason_code','like','%'.$request->search.'%')
					->orWhere('reason_code', 'like','%'.$request->search.'%')
					->orderBy('reason_code')
					->paginate($this->paginateValue);

			return view('maintenance_reasons.index', [
					'maintenance_reasons'=>$maintenance_reasons,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$maintenance_reasons = DB::table('ref_maintenance_reasons')
					->where('reason_code','=',$id)
					->paginate($this->paginateValue);

			return view('maintenance_reasons.index', [
					'maintenance_reasons'=>$maintenance_reasons
			]);
	}
}
