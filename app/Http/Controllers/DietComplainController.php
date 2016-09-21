<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietComplain;
use Log;
use DB;
use Session;
use App\Ward;
use App\DietPeriod as Period;
use App\DietMeal as Meal;
use App\DietContamination as Contamination;
use Carbon\Carbon;

class DietComplainController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_complains = DB::table('diet_complains as a')
					->leftJoin('wards as b', 'b.ward_code', '=', 'a.ward_code')
					->orderBy('complain_date')
					->paginate($this->paginateValue);

			return view('diet_complains.index', [
					'diet_complains'=>$diet_complains
			]);
	}

	public function create()
	{
			$diet_complain = new DietComplain();
			return view('diet_complains.create', [
					'diet_complain' => $diet_complain,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'meal' => Meal::all()->sortBy('meal_name')->lists('meal_name', 'meal_code')->prepend('',''),
					'contamination' => Contamination::all()->sortBy('contamination_name')->lists('contamination_name', 'contamination_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					]);
	}

	public function store(Request $request) 
	{
			$diet_complain = new DietComplain();
			$valid = $diet_complain->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_complain = new DietComplain($request->all());
					$diet_complain->complain_id = $request->complain_id;
					$diet_complain->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_complains/id/'.$diet_complain->complain_id);
			} else {
					return redirect('/diet_complains/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_complain = DietComplain::findOrFail($id);
			return view('diet_complains.edit', [
					'diet_complain'=>$diet_complain,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'meal' => Meal::all()->sortBy('meal_name')->lists('meal_name', 'meal_code')->prepend('',''),
					'contamination' => Contamination::all()->sortBy('contamination_name')->lists('contamination_name', 'contamination_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_complain = DietComplain::findOrFail($id);
			$diet_complain->fill($request->input());

			$diet_complain->complain_reported = $request->complain_reported ?: 0;

			$valid = $diet_complain->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_complain->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_complains/id/'.$id);
			} else {
					return view('diet_complains.edit', [
							'diet_complain'=>$diet_complain,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'meal' => Meal::all()->sortBy('meal_name')->lists('meal_name', 'meal_code')->prepend('',''),
					'contamination' => Contamination::all()->sortBy('contamination_name')->lists('contamination_name', 'contamination_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_complain = DietComplain::findOrFail($id);
		return view('diet_complains.destroy', [
			'diet_complain'=>$diet_complain
			]);

	}
	public function destroy($id)
	{	
			DietComplain::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_complains');
	}
	
	public function search(Request $request)
	{
			$diet_complains = DB::table('diet_complains')
					->where('complain_date','like','%'.$request->search.'%')
					->orWhere('complain_id', 'like','%'.$request->search.'%')
					->orderBy('complain_date')
					->paginate($this->paginateValue);

			return view('diet_complains.index', [
					'diet_complains'=>$diet_complains,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_complains = DB::table('diet_complains as a')
					->leftJoin('wards as b', 'b.ward_code', '=', 'a.ward_code')
					->where('complain_id','=',$id)
					->paginate($this->paginateValue);

			return view('diet_complains.index', [
					'diet_complains'=>$diet_complains
			]);
	}
}
