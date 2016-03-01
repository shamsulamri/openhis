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


class AppointmentServiceController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$appointment_services = DB::table('appointment_services')
					->orderBy('service_name')
					->paginate($this->paginateValue);
			return view('appointment_services.index', [
					'appointment_services'=>$appointment_services
			]);
	}

	public function create()
	{
			$appointment_service = new AppointmentService();
			return view('appointment_services.create', [
					'appointment_service' => $appointment_service,
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
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
			return view('appointment_services.edit', [
					'appointment_service'=>$appointment_service,
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
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
			$appointment_services = DB::table('appointment_services')
					->where('service_id','=',$id)
					->paginate($this->paginateValue);

			return view('appointment_services.index', [
					'appointment_services'=>$appointment_services
			]);
	}
}
