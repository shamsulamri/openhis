<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietQuality;
use Log;
use DB;
use Session;
use App\DietPeriod as Period;
use App\DietClass;
use App\DietRating;
use Carbon\Carbon;

class DietQualityController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_qualities = DB::table('diet_qualities as a')
					->leftjoin('diet_periods as b', 'b.period_code', '=', 'a.period_code')
					->orderBy('qc_date')
					->paginate($this->paginateValue);

			return view('diet_qualities.index', [
					'diet_qualities'=>$diet_qualities
			]);
	}

	public function create()
	{
			$diet_quality = new DietQuality();
			return view('diet_qualities.create', [
					'diet_quality' => $diet_quality,
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'rating' => DietRating::all()->sortBy('rate_name')->lists('rate_name', 'rate_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					]);
	}

	public function store(Request $request) 
	{
			$diet_quality = new DietQuality();
			$valid = $diet_quality->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_quality = new DietQuality($request->all());
					$diet_quality->qc_id = $request->qc_id;
					$diet_quality->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_qualities/id/'.$diet_quality->qc_id);
			} else {
					return redirect('/diet_qualities/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_quality = DietQuality::findOrFail($id);
			return view('diet_qualities.edit', [
					'diet_quality'=>$diet_quality,
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					'rating' => DietRating::all()->sortBy('rate_name')->lists('rate_name', 'rate_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_quality = DietQuality::findOrFail($id);
			$diet_quality->fill($request->input());


			$valid = $diet_quality->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_quality->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_qualities/id/'.$id);
			} else {
					return view('diet_qualities.edit', [
							'diet_quality'=>$diet_quality,
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_quality = DietQuality::findOrFail($id);
		return view('diet_qualities.destroy', [
			'diet_quality'=>$diet_quality
			]);

	}
	public function destroy($id)
	{	
			DietQuality::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_qualities');
	}
	
	public function search(Request $request)
	{
			$diet_qualities = DB::table('diet_qualities as a')
					->leftjoin('diet_periods as b', 'b.period_code', '=', 'a.period_code')
					->where('qc_date','like','%'.$request->search.'%')
					->orWhere('period_name', 'like','%'.$request->search.'%')
					->orderBy('qc_date');
				
			$diet_qualities = $diet_qualities->paginate($this->paginateValue);

			return view('diet_qualities.index', [
					'diet_qualities'=>$diet_qualities,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_qualities = DB::table('diet_qualities as a')
					->leftjoin('diet_periods as b', 'b.period_code', '=', 'a.period_code')
					->where('qc_id','=',$id)
					->paginate($this->paginateValue);

			return view('diet_qualities.index', [
					'diet_qualities'=>$diet_qualities
			]);
	}
}
