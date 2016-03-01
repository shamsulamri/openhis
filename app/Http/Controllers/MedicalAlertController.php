<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MedicalAlert;
use Log;
use DB;
use Session;


class MedicalAlertController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$medical_alerts = DB::table('medical_alerts')
					->orderBy('patient_id')
					->paginate($this->paginateValue);
			return view('medical_alerts.index', [
					'medical_alerts'=>$medical_alerts
			]);
	}

	public function create()
	{
			$medical_alert = new MedicalAlert();
			return view('medical_alerts.create', [
					'medical_alert' => $medical_alert,
				
					]);
	}

	public function store(Request $request) 
	{
			$medical_alert = new MedicalAlert();
			$valid = $medical_alert->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$medical_alert = new MedicalAlert($request->all());
					$medical_alert->alert_id = $request->alert_id;
					$medical_alert->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/medical_alerts/id/'.$medical_alert->alert_id);
			} else {
					return redirect('/medical_alerts/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$medical_alert = MedicalAlert::findOrFail($id);
			return view('medical_alerts.edit', [
					'medical_alert'=>$medical_alert,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$medical_alert = MedicalAlert::findOrFail($id);
			$medical_alert->fill($request->input());


			$valid = $medical_alert->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$medical_alert->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/medical_alerts/id/'.$id);
			} else {
					return view('medical_alerts.edit', [
							'medical_alert'=>$medical_alert,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$medical_alert = MedicalAlert::findOrFail($id);
		return view('medical_alerts.destroy', [
			'medical_alert'=>$medical_alert
			]);

	}
	public function destroy($id)
	{	
			MedicalAlert::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/medical_alerts');
	}
	
	public function search(Request $request)
	{
			$medical_alerts = DB::table('medical_alerts')
					->where('patient_id','like','%'.$request->search.'%')
					->orWhere('alert_id', 'like','%'.$request->search.'%')
					->orderBy('patient_id')
					->paginate($this->paginateValue);

			return view('medical_alerts.index', [
					'medical_alerts'=>$medical_alerts,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$medical_alerts = DB::table('medical_alerts')
					->where('alert_id','=',$id)
					->paginate($this->paginateValue);

			return view('medical_alerts.index', [
					'medical_alerts'=>$medical_alerts
			]);
	}
}
