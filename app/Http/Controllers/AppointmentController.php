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
use App\DojoUtility;

class AppointmentController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{

			$appointments = Appointment::orderBy('appointment_id')
					->where('appointment_datetime', '>=', Carbon::today());

			if (!empty(Auth::user()->service_id)) {
				$appointments = $appointments->where('service_id', '=', Auth::user()->service_id);
			}
					
			$appointments = $appointments->paginate($this->paginateValue);

			return view('appointments.index', [
					'appointments'=>$appointments,
					'services' => Service::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'service' => Auth::user()->service_id,
					'ward' => Ward::where('ward_code', $request->cookie('ward'))->first(),
					'date_start'=>DojoUtility::today(),
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
						//return redirect('/appointments/id/'.$appointment->appointment_id);
						return redirect('/appointments');
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
					'date_start'=>null,
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
					//return redirect('/appointments/id/'.$id);
					return redirect('/appointments');
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
			'appointment'=>$appointment,
			'patient'=>$appointment->patient,
			]);

	}
	public function destroy(Request $request, $id)
	{	
			$appointment = Appointment::find($id);
			$appointment->appointment_cancel =$request->appointment_cancel;
			$appointment->save();

			Appointment::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/appointments');
	}
	
	public function search(Request $request)
	{
			$date_start = null;
			if (!empty($request->date_start)) {
					$date_start = DojoUtility::dateTimeWriteFormat($request->date_start.' 00:00');
			} else {
					$date_start = DojoUtility::dateTimeWriteFormat(DojoUtility::today().' 00:00');
			}

			$appointments = Appointment::orderBy('appointment_id')
					->leftJoin('patients as b', 'appointments.patient_id', '=', 'b.patient_id');


			if (!empty($request->search)) {
					$appointments = $appointments->where(function ($query) use ($request) {
							$query->where('patient_name','like', '%'.$request->search.'%')
									->orWhere('patient_mrn','like', '%'.$request->search.'%');
					});
			}

			if (!empty($request->services)) {
				$appointments = $appointments->where('service_id', '=', $request->services);
			}

			if (!empty($request->date_start)) {
				$date_offset = DojoUtility::addDays($request->date_start,1);
				$date_offset = DojoUtility::dateTimeWriteFormat($request->date_start.' 23:59');
				$appointments = $appointments->whereBetween('appointment_datetime', array($date_start, $date_offset));
			} else {
				$appointments = $appointments->where('appointment_datetime', '>=', Carbon::today());
			}

			$appointments = $appointments->orderBy('appointment_id')
					->paginate($this->paginateValue);

			return view('appointments.index', [
					'appointments'=>$appointments,
					'services' => Service::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'service' => $request->services,
					'search' => $request->search,
					'date_start'=>DojoUtility::dateReadFormat($date_start),
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
					'date_start'=>null,
			]);
	}

	public function bulkDelete(Request $request)
	{
			foreach ($request->all() as $id=>$value) {
					switch ($id) {
							case '_token':
									break;
							default:
									Appointment::find($id)->delete();
					}
			}

			Session::flash('message', 'Record deleted.');
			return redirect('/appointments');
	}

	public function enquiry(Request $request)
	{
			$request->search = trim($request->search);
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			if (empty($request->date_start)) {
					$date_start = DojoUtility::dateTimeWriteFormat(DojoUtility::today().' 00:00');
			}
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$appointment_status = array(''=>'','cancel'=>'Cancel');
			$appointments = Appointment::select('appointment_datetime', 'patient_name', 'patient_mrn', 'patient_phone_mobile', 'patient_phone_home', 'service_name', 'appointment_cancel', 'appointment_description')
					->leftJoin('patients as b', 'appointments.patient_id', '=', 'b.patient_id')
					->leftJoin('appointment_services as c', 'appointments.service_id', '=', 'c.service_id')
					->orderBy('appointment_id', 'desc');


			if (!empty($request->search)) {
					$appointments = $appointments->where(function ($query) use ($request) {
							$query->where('patient_name','like', '%'.$request->search.'%')
									->orWhere('patient_mrn','like', '%'.$request->search.'%');
					});
			}

			if (!empty($request->services)) {
				$appointments = $appointments->where('appointments.service_id', '=', $request->services);
			}

			if (!empty($date_start) && empty($request->date_end)) {
				$appointments = $appointments->where('appointment_datetime', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$appointments = $appointments->where('appointment_datetime', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$appointments = $appointments->whereBetween('appointment_datetime', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if (!empty($request->status_code)) {
					$appointments = $appointments->onlyTrashed();
			}

			if ($request->export_report) {
					DojoUtility::export_report($appointments->get());
			}

			$appointments = $appointments->orderBy('appointment_id')
					->paginate($this->paginateValue);

			return view('appointments.enquiry', [
					'appointments'=>$appointments,
					'services' => Service::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'service' => $request->services,
					'search' => $request->search,
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'appointment_status'=>$appointment_status,
					'status_code'=>$request->status_code,
					]);
	}
}
