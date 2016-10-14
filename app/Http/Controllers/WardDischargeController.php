<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\WardDischarge;
use Log;
use DB;
use Session;
use App\Order;
use App\MedicalCertificate;
use App\Encounter;
use App\Bill;
use App\Admission;
use App\Appointment;

class WardDischargeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$ward_discharges = DB::table('ward_discharges')
					->orderBy('discharge_description')
					->paginate($this->paginateValue);
			return view('ward_discharges.index', [
					'ward_discharges'=>$ward_discharges
			]);
	}

	public function create($admission_id)
	{
			$admission = Admission::find($admission_id);
			$encounter = $admission->encounter;
			$encounter_id = $encounter->encounter_id;
			$ward_discharge = new WardDischarge();
			$ward_discharge->encounter_id = $encounter_id;

			$orders = Order::where('encounter_id',$encounter_id)
						->where('order_is_discharge',1)
						->get();

			$mc = MedicalCertificate::where('encounter_id', $encounter_id)->get();

			$bill = Bill::where('encounter_id', $encounter_id)->get();

			$appointments = Appointment::where('admission_id', $admission_id)
					->whereDate('appointment_datetime','>',$encounter->created_at)
					->get();
			
			$service_id = $admission->user_id;

			return view('ward_discharges.create', [
					'ward_discharge' => $ward_discharge,
					'mc'=>$mc,
					'discharge_orders'=>$orders,	
					'patient'=>$encounter->patient,
					'encounter'=>$encounter,
					'bill'=>$bill,
					'service_id'=>$service_id,
					'admission_id'=>$admission_id,
					'appointments'=>$appointments,
					]);
	}

	public function store(Request $request) 
	{
			$ward_discharge = new WardDischarge();
			$ward_discharge->encounter_id = $request->encounter_id;
			$valid = $ward_discharge->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$ward_discharge = new WardDischarge($request->all());
					$ward_discharge->discharge_id = $request->discharge_id;
					$ward_discharge->encounter_id = $request->encounter_id;
					$ward_discharge->save();
					Session::flash('message', 'Patient has been physically discharged.');
					return redirect('admissions');
			} else {
					return redirect('/ward_discharges/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$ward_discharge = WardDischarge::findOrFail($id);
			return view('ward_discharges.edit', [
					'ward_discharge'=>$ward_discharge,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$ward_discharge = WardDischarge::findOrFail($id);
			$ward_discharge->fill($request->input());


			$valid = $ward_discharge->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$ward_discharge->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/ward_discharges/id/'.$id);
			} else {
					return view('ward_discharges.edit', [
							'ward_discharge'=>$ward_discharge,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$ward_discharge = WardDischarge::findOrFail($id);
		return view('ward_discharges.destroy', [
			'ward_discharge'=>$ward_discharge
			]);

	}
	public function destroy($id)
	{	
			WardDischarge::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/ward_discharges');
	}
	
	public function search(Request $request)
	{
			$ward_discharges = DB::table('ward_discharges')
					->where('discharge_description','like','%'.$request->search.'%')
					->orWhere('discharge_id', 'like','%'.$request->search.'%')
					->orderBy('discharge_description')
					->paginate($this->paginateValue);

			return view('ward_discharges.index', [
					'ward_discharges'=>$ward_discharges,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$ward_discharges = DB::table('ward_discharges')
					->where('discharge_id','=',$id)
					->paginate($this->paginateValue);

			return view('ward_discharges.index', [
					'ward_discharges'=>$ward_discharges
			]);
	}
}
