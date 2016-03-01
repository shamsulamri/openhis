<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietPeriod;
use Log;
use DB;
use Session;


class DietPeriodController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_periods = DB::table('diet_periods')
					->orderBy('period_name')
					->paginate($this->paginateValue);
			return view('diet_periods.index', [
					'diet_periods'=>$diet_periods
			]);
	}

	public function create()
	{
			$diet_period = new DietPeriod();
			return view('diet_periods.create', [
					'diet_period' => $diet_period,
				
					]);
	}

	public function store(Request $request) 
	{
			$diet_period = new DietPeriod();
			$valid = $diet_period->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_period = new DietPeriod($request->all());
					$diet_period->period_code = $request->period_code;
					$diet_period->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_periods/id/'.$diet_period->period_code);
			} else {
					return redirect('/diet_periods/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_period = DietPeriod::findOrFail($id);
			return view('diet_periods.edit', [
					'diet_period'=>$diet_period,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_period = DietPeriod::findOrFail($id);
			$diet_period->fill($request->input());


			$valid = $diet_period->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_period->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_periods/id/'.$id);
			} else {
					return view('diet_periods.edit', [
							'diet_period'=>$diet_period,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_period = DietPeriod::findOrFail($id);
		return view('diet_periods.destroy', [
			'diet_period'=>$diet_period
			]);

	}
	public function destroy($id)
	{	
			DietPeriod::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_periods');
	}
	
	public function search(Request $request)
	{
			$diet_periods = DB::table('diet_periods')
					->where('period_name','like','%'.$request->search.'%')
					->orWhere('period_code', 'like','%'.$request->search.'%')
					->orderBy('period_name')
					->paginate($this->paginateValue);

			return view('diet_periods.index', [
					'diet_periods'=>$diet_periods,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_periods = DB::table('diet_periods')
					->where('period_code','=',$id)
					->paginate($this->paginateValue);

			return view('diet_periods.index', [
					'diet_periods'=>$diet_periods
			]);
	}
}
