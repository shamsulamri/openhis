<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AppointmentService;
use Log;
use DB;
use Session;
use App\Department;
use Carbon\Carbon;
use App\Patient;
use App\Appointment;
use App\User;

class AppointmentServiceController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$appointment_services = DB::table('appointment_services as a')
					->leftjoin('departments as b', 'b.department_code','=','a.department_code')
					->orderBy('service_name')
					->paginate($this->paginateValue);

			return view('appointment_services.index', [
					'appointment_services'=>$appointment_services,
			]);
	}

	public function create()
	{
			$appointment_service = new AppointmentService();
			$consultants = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('module_consultation',1)
							->orderBy('name')
							->lists('name','id')
							->prepend('','');
			return view('appointment_services.create', [
					'appointment_service' => $appointment_service,
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					'consultants'=>$consultants,
					]);
	}

	public function store(Request $request) 
	{
			$appointment_service = new AppointmentService();
			$valid = $appointment_service->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$appointment_service = new AppointmentService($request->all());
					$appointment_service->service_id = $request->service_id;
					$appointment_service->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/appointment_services/id/'.$appointment_service->service_id);
			} else {
					return redirect('/appointment_services/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$appointment_service = AppointmentService::findOrFail($id);
			$consultants = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('module_consultation',1)
							->orderBy('name')
							->lists('name','id')
							->prepend('','');
			return view('appointment_services.edit', [
					'appointment_service'=>$appointment_service,
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					'consultants'=>$consultants,
					]);
	}

	public function update(Request $request, $id) 
	{
			$appointment_service = AppointmentService::findOrFail($id);
			$appointment_service->fill($request->input());

			$appointment_service->service_monday = $request->service_monday ?: 0;
			$appointment_service->service_tuesday = $request->service_tuesday ?: 0;
			$appointment_service->service_wednesday = $request->service_wednesday ?: 0;
			$appointment_service->service_thursday = $request->service_thursday ?: 0;
			$appointment_service->service_friday = $request->service_friday ?: 0;
			$appointment_service->service_saturday = $request->service_saturday ?: 0;
			$appointment_service->service_sunday = $request->service_sunday ?: 0;

			$valid = $appointment_service->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$appointment_service->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/appointment_services/id/'.$id);
			} else {
					return view('appointment_services.edit', [
							'appointment_service'=>$appointment_service,
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$appointment_service = AppointmentService::findOrFail($id);
		return view('appointment_services.destroy', [
			'appointment_service'=>$appointment_service
			]);

	}
	public function destroy($id)
	{	
			AppointmentService::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/appointment_services');
	}
	
	public function search(Request $request)
	{
			$appointment_services = DB::table('appointment_services')
					->where('service_name','like','%'.$request->search.'%')
					->orWhere('service_id', 'like','%'.$request->search.'%')
					->orderBy('service_name')
					->paginate($this->paginateValue);

			return view('appointment_services.index', [
					'appointment_services'=>$appointment_services,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$appointment_services = DB::table('appointment_services as a')
					->leftjoin('departments as b', 'b.department_code','=','a.department_code')
					->where('service_id','=',$id)
					->paginate($this->paginateValue);

			$consultants = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('module_consultation',1)
							->orderBy('name')
							->lists('name','id')
							->prepend('','');
			return view('appointment_services.index', [
					'appointment_services'=>$appointment_services,
					'consultants'=>$consultants
			]);
	}

	public function show(Request $request, $id, $selected_week, $service_id=null, $appointment_id=null)
	{
			$services = null;
			$service_path = "";

			if (empty($request->services)) {
					$services = AppointmentService::all();
					$services = null;
			} else {
					$services = AppointmentService::where('service_id', $request->services)->get();
					$service_id = $request->services;		
			}

			if ($service_id != null) { 
					$service_path="/".$service_id;
					$services = AppointmentService::where('service_id', $service_id)->get();
			}
			//$d = strtotime('today');
			//$start_week = strtotime('last monday midnight', $d);
			//$end_week = strtotime('next sunday', $d);
			//$start = date('d/m/Y', $start_week);
			//$end = date('d/m/Y', $end_week);

			$d = Carbon::now();
			$start_week = new Carbon('monday this week');
			$start_week = $start_week->addWeeks($selected_week);
			$end_week = new Carbon('next sunday'); 

			$week=array(Carbon::create($start_week->year, $start_week->month, $start_week->day));

			for ($i=0;$i<6;$i++) {
					$start_week = $start_week->addDays(1);
					array_push($week, Carbon::create($start_week->year, $start_week->month, $start_week->day));
			}

			//$appointments = Appointment::all(['appointment_id','appointment_slot'])->toArray();
			//$services = AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('','');
			//return $services;

			$appointment=null;

			$admission_id = 0;
			if (isset($request->admission_id)) {
				$admission_id = $request->admission_id;
			}

			if ($appointment_id != null) {
					$appointment = Appointment::find($appointment_id);
					$service_path = $service_path.'/'.$appointment_id;
			}

			return view('appointment_services.render', [
					'services'=>$services,
					'start'=>$start_week,
					'end'=>$end_week,
					'week'=> $week,
					'patient'=>Patient::find($id),
					'appointment_model'=>new AppointmentService(),
					'today'=>Carbon::now(),
					'selected_week'=>$selected_week,
					'menu_services' => AppointmentService::all()->sortBy('service_name')->lists('service_name', 'service_id')->prepend('',''),
					'service'=>$service_id,
					'service_path'=>$service_path,
					'appointment_id'=>$appointment_id, 
					'appointment'=>$appointment, 
					'admission_id'=>$admission_id, 
					]);

	}

}
