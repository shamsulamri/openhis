<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\FeeSchedule;
use Log;
use DB;
use Session;


class FeeScheduleController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$fee_schedules = FeeSchedule::orderBy('fee_code')
					->paginate($this->paginateValue);

			return view('fee_schedules.index', [
					'fee_schedules'=>$fee_schedules
			]);
	}

	public function create()
	{
			$fee_schedule = new FeeSchedule();
			return view('fee_schedules.create', [
					'fee_schedule' => $fee_schedule,
				
					]);
	}

	public function store(Request $request) 
	{
			$fee_schedule = new FeeSchedule();
			$valid = $fee_schedule->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$fee_schedule = new FeeSchedule($request->all());
					$fee_schedule->fee_code = $request->fee_code;
					$fee_schedule->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/fee_schedules/id/'.$fee_schedule->fee_code);
			} else {
					return redirect('/fee_schedules/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$fee_schedule = FeeSchedule::findOrFail($id);
			return view('fee_schedules.edit', [
					'fee_schedule'=>$fee_schedule,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$fee_schedule = FeeSchedule::findOrFail($id);
			$fee_schedule->fill($request->input());


			$valid = $fee_schedule->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$fee_schedule->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/fee_schedules/id/'.$id);
			} else {
					return view('fee_schedules.edit', [
							'fee_schedule'=>$fee_schedule,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$fee_schedule = FeeSchedule::findOrFail($id);
		return view('fee_schedules.destroy', [
			'fee_schedule'=>$fee_schedule
			]);

	}
	public function destroy($id)
	{	
			FeeSchedule::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/fee_schedules');
	}
	
	public function search(Request $request)
	{
			$fee_schedules = FeeSchedule::where('fee_code','like','%'.$request->search.'%')
					->orWhere('fee_code', 'like','%'.$request->search.'%')
					->orderBy('fee_code')
					->paginate($this->paginateValue);

			return view('fee_schedules.index', [
					'fee_schedules'=>$fee_schedules,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$fee_schedules = FeeSchedule::where('fee_code','=',$id)
					->paginate($this->paginateValue);

			return view('fee_schedules.index', [
					'fee_schedules'=>$fee_schedules
			]);
	}
}
