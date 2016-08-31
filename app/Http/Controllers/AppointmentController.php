<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Appointment;
use Log;
use DB;
use Session;
use App\AppointmentService as Service;
use Carbon\Carbon;
use App\Patient;
use App\Ward;
use Auth;

class AppointmentController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$appointments = DB::table('appointments as a')
					->leftJoin('patients as b', 'a.patient_id', '=', 'b.patient_id')
					->leftJoin('appointment_services as c', 'c.service_id', '=', 'a.service_id')
					->where('appointment_datetime', '>=', Carbon::today())
					->orderBy('appointment_id')
					->paginate($this->paginateValue);
			return view('appointments.index', [
					'appointments'=>$appointments,
					'services' => Service::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'service' => '',
					'ward' => Ward::where('ward_code', $request->cookie('ward'))->first(),
			]);
	}

	public function create($patient_id, $service_id, $slot, $admission_id)
	{
			$appointment = new Appointment();
			$appointment->patient_id = $patient_id;
			$appointment->service_id = $service_id;
			$appointment->appointment_slot = $slot;

			$year = substr($appointment->appointment_slot,0,4);
			$month = substr($appointment->appointment_slot,4,2);
			$day = substr($appointment->appointment_slot,6,2);
			$hour = substr($appointment->appointment_slot,8,2);
			$minute = substr($appointment->appointment_slot,10,2);
			$appointment_datetime = Carbon::create($year, $month, $day, $hour, $minute);
			$service_name = Service::find($service_id)->service_name;

			return view('appointments.create', [
					'appointment' => $appointment,
					'service' => Service::all()->sortBy('service_name')->lists('service_name', 'service_code')->prepend('',''),
					'patient' => Patient::find($patient_id),
					'appointment_datetime' => $appointment_datetime,
					'service_name' => $service_name,
					'admission_id' => $admission_id,
					]);
	}

	public function store(Request $request) 
	{
			$appointment = new Appointment();
			$valid = $appointment->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$appointment = new Appointment($request->all());
					$appointment->appointment_id = $request->appointment_id;
					
					$year = substr($appointment->appointment_slot,0,4);
					$month = substr($appointment->appointment_slot,4,2);
					$day = substr($appointment->appointment_slot,6,2);
					$hour = substr($appointment->appointment_slot,8,2);
					$minute = substr($appointment->appointment_slot,10,2);
					
					$appointment_datetime = Carbon::create($year, $month, $day, $hour, $minute);

					$appointment->appointment_datetime = $appointment_datetime;
					$appointment->save();
					Session::flash('message', 'Record successfully created.');
					if (Auth::user()->can('module-ward')) {
						return redirect('/ward_discharges/create/'.$appointment->admission_id);
					} else {
						return redirect('/appointments/id/'.$appointment->appointment_id);
					}
			} else {
					return redirect('/appointments/create/'.$request->patient_id.'/'.$request->service_id.'/'.$request->appointment_slot)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id, $appointment_slot) 
	{
			$appointment = Appointment::findOrFail($id);
			$appointment->appointment_slot = $appointment_slot;
			$service_name = Service::find($appointment->service_id)->service_name;

			$year = substr($appointment->appointment_slot,0,4);
			$month = substr($appointment->appointment_slot,4,2);
			$day = substr($appointment->appointment_slot,6,2);
			$hour = substr($appointment->appointment_slot,8,2);
			$minute = substr($appointment->appointment_slot,10,2);
			$appointment_datetime = Carbon::create($year, $month, $day, $hour, $minute);
			$appointment->appointment_datetime = $appointment_datetime;

			return view('appointments.edit', [
					'appointment'=>$appointment,
					'service_name'=>$service_name,
					'service' => Service::all()->sortBy('service_name')->lists('service_name', 'service_code')->prepend('',''),
					'patient' => Patient::find($appointment->patient_id),
					'appointment_datetime' => $appointment->appointment_datetime,
					'admission_id'=>null,
					]);
	}

	public function update(Request $request, $id) 
	{
			$appointment = Appointment::findOrFail($id);
			$appointment->fill($request->input());


			$valid = $appointment->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$year = substr($appointment->appointment_slot,0,4);
					$month = substr($appointment->appointment_slot,4,2);
					$day = substr($appointment->appointment_slot,6,2);
					$hour = substr($appointment->appointment_slot,8,2);
					$minute = substr($appointment->appointment_slot,10,2);
					$appointment_datetime = Carbon::create($year, $month, $day, $hour, $minute);
					$appointment->appointment_datetime = $appointment_datetime;
					$appointment->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/appointments/id/'.$id);
			} else {
					return view('appointments.edit', [
							'appointment'=>$appointment,
					'service' => Service::all()->sortBy('service_name')->lists('service_name', 'service_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$appointment = Appointment::findOrFail($id);
		return view('appointments.destroy', [
			'appointment'=>$appointment
			]);

	}
	public function destroy($id)
	{	
			Appointment::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/appointments');
	}
	
	public function search(Request $request)
	{
			$appointments = DB::table('appointments as a')
					->leftJoin('patients as b', 'a.patient_id', '=', 'b.patient_id')
					->leftJoin('appointment_services as c', 'c.service_id', '=', 'a.service_id')
					->where('appointment_datetime', '>=', Carbon::today())
					->where('a.service_id', '=', $request->services)
					->orderBy('appointment_id');

			Log::info($appointments->toSql());
			$appointments = $appointments->paginate($this->paginateValue);

			return view('appointments.index', [
					'appointments'=>$appointments,
					'services' => Service::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'service' => $request->services,
					]);
	}

	public function searchById($id)
	{
			$appointments = DB::table('appointments as a')
					->leftJoin('patients as b', 'a.patient_id', '=', 'b.patient_id')
					->leftJoin('appointment_services as c', 'c.service_id', '=', 'a.service_id')
					->where('appointment_id','=',$id)
					->orderBy('appointment_id')
					->paginate($this->paginateValue);

			return view('appointments.index', [
					'appointments'=>$appointments,
					'services' => Service::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'service' => '',
			]);
	}
}
