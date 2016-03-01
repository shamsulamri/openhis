<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Period;
use Log;
use DB;
use Session;


class PeriodController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$periods = DB::table('ref_periods')
					->orderBy('period_name')
					->paginate($this->paginateValue);
			return view('periods.index', [
					'periods'=>$periods
			]);
	}

	public function create()
	{
			$period = new Period();
			return view('periods.create', [
					'period' => $period,
				
					]);
	}

	public function store(Request $request) 
	{
			$period = new Period();
			$valid = $period->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$period = new Period($request->all());
					$period->period_code = $request->period_code;
					$period->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/periods/id/'.$period->period_code);
			} else {
					return redirect('/periods/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$period = Period::findOrFail($id);
			return view('periods.edit', [
					'period'=>$period,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$period = Period::findOrFail($id);
			$period->fill($request->input());


			$valid = $period->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$period->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/periods/id/'.$id);
			} else {
					return view('periods.edit', [
							'period'=>$period,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$period = Period::findOrFail($id);
		return view('periods.destroy', [
			'period'=>$period
			]);

	}
	public function destroy($id)
	{	
			Period::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/periods');
	}
	
	public function search(Request $request)
	{
			$periods = DB::table('ref_periods')
					->where('period_name','like','%'.$request->search.'%')
					->orWhere('period_code', 'like','%'.$request->search.'%')
					->orderBy('period_name')
					->paginate($this->paginateValue);

			return view('periods.index', [
					'periods'=>$periods,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$periods = DB::table('ref_periods')
					->where('period_code','=',$id)
					->paginate($this->paginateValue);

			return view('periods.index', [
					'periods'=>$periods
			]);
	}
}
