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


class AppointmentController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$appointments = DB::table('appointments')
					->orderBy('patient_id')
					->paginate($this->paginateValue);
			return view('appointments.index', [
					'appointments'=>$appointments
			]);
	}

	public function create()
	{
			$appointment = new Appointment();
			return view('appointments.create', [
					'appointment' => $appointment,
					'service' => Service::all()->sortBy('service_name')->lists('service_name', 'service_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$appointment = new Appointment();
			$valid = $appointment->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$appointment = new Appointment($request->all());
					$appointment->appointment_id = $request->appointment_id;
					$appointment->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/appointments/id/'.$appointment->appointment_id);
			} else {
					return redirect('/appointments/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$appointment = Appointment::findOrFail($id);
			return view('appointments.edit', [
					'appointment'=>$appointment,
					'service' => Service::all()->sortBy('service_name')->lists('service_name', 'service_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$appointment = Appointment::findOrFail($id);
			$appointment->fill($request->input());


			$valid = $appointment->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
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
			$appointments = DB::table('appointments')
					->where('patient_id','like','%'.$request->search.'%')
					->orWhere('appointment_id', 'like','%'.$request->search.'%')
					->orderBy('patient_id')
					->paginate($this->paginateValue);

			return view('appointments.index', [
					'appointments'=>$appointments,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$appointments = DB::table('appointments')
					->where('appointment_id','=',$id)
					->paginate($this->paginateValue);

			return view('appointments.index', [
					'appointments'=>$appointments
			]);
	}
}
