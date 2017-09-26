<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietCensus;
use Log;
use DB;
use Session;
use App\Diet;
use App\DojoUtility;

class DietCensusController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_censuses = DB::table('diet_censuses')
					->orderBy('census_date')
					->paginate($this->paginateValue);
			return view('diet_censuses.index', [
					'diet_censuses'=>$diet_censuses
			]);
	}

	public function create()
	{
			$diet_census = new DietCensus();
			return view('diet_censuses.create', [
					'diet_census' => $diet_census,
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$diet_census = new DietCensus();
			$valid = $diet_census->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_census = new DietCensus($request->all());
					$diet_census->census_id = $request->census_id;
					$diet_census->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_censuses/id/'.$diet_census->census_id);
			} else {
					return redirect('/diet_censuses/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_census = DietCensus::findOrFail($id);
			return view('diet_censuses.edit', [
					'diet_census'=>$diet_census,
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_census = DietCensus::findOrFail($id);
			$diet_census->fill($request->input());


			$valid = $diet_census->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_census->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_censuses/id/'.$id);
			} else {
					return view('diet_censuses.edit', [
							'diet_census'=>$diet_census,
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_census = DietCensus::findOrFail($id);
		return view('diet_censuses.destroy', [
			'diet_census'=>$diet_census
			]);

	}
	public function destroy($id)
	{	
			DietCensus::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_censuses');
	}
	
	public function search(Request $request)
	{
			$diet_censuses = DB::table('diet_censuses')
					->where('census_date','like','%'.$request->search.'%')
					->orWhere('census_id', 'like','%'.$request->search.'%')
					->orderBy('census_date')
					->paginate($this->paginateValue);

			return view('diet_censuses.index', [
					'diet_censuses'=>$diet_censuses,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_censuses = DB::table('diet_censuses')
					->where('census_id','=',$id)
					->paginate($this->paginateValue);

			return view('diet_censuses.index', [
					'diet_censuses'=>$diet_censuses
			]);
	}

	public function enquiry(Request $request)
	{

			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$diet_censuses = DietCensus::select('census_date', 'diet_name', 'census_count')
					->leftJoin('diets as b', 'b.diet_code', '=', 'diet_censuses.diet_code')
					->orderBy('census_date');

			if (!empty($request->diet_code)) {
					$diet_censuses = $diet_censuses->where('b.diet_code','=', $request->diet_code);
			}

			if (!empty($date_start) && empty($request->date_end)) {
				$diet_censuses = $diet_censuses->where('diet_censuses.census_date', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$diet_censuses = $diet_censuses->where('diet_censuses.census_date', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$diet_censuses = $diet_censuses->whereBetween('diet_censuses.census_date', array($date_start.' 00:00', $date_end.' 23:59'));
			} 
			if ($request->export_report) {
				DojoUtility::export_report($diet_censuses->get());
			}

			$diet_censuses = $diet_censuses->paginate($this->paginateValue);

			return view('diet_censuses.enquiry', [
					'diet_censuses'=>$diet_censuses,
					'search'=>$request->search,
					'diets' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					'diet_code'=>$request->diet_code,
					'date_start'=>$date_start,
					'date_end'=>$date_end,
			]);
	}
}
