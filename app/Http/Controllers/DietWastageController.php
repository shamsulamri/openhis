<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietWastage;
use Log;
use DB;
use Session;
use App\Ward;
use App\DietPeriod as Period;
use Carbon\Carbon;

class DietWastageController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_wastages = DB::table('diet_wastages as a')
					->leftjoin('diet_periods as b','b.period_code','=', 'a.period_code')
					->leftjoin('wards as c','c.ward_code','=', 'a.ward_code')
					->orderBy('waste_date')
					->paginate($this->paginateValue);
			return view('diet_wastages.index', [
					'diet_wastages'=>$diet_wastages
			]);
	}

	public function create()
	{
			$diet_wastage = new DietWastage();
			return view('diet_wastages.create', [
					'diet_wastage' => $diet_wastage,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					]);
	}

	public function store(Request $request) 
	{
			$diet_wastage = new DietWastage();
			$valid = $diet_wastage->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_wastage = new DietWastage($request->all());
					$diet_wastage->waste_id = $request->waste_id;
					$diet_wastage->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_wastages/id/'.$diet_wastage->waste_id);
			} else {
					return redirect('/diet_wastages/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_wastage = DietWastage::findOrFail($id);
			return view('diet_wastages.edit', [
					'diet_wastage'=>$diet_wastage,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'minYear' => Carbon::now()->year,
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_wastage = DietWastage::findOrFail($id);
			$diet_wastage->fill($request->input());


			$valid = $diet_wastage->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_wastage->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_wastages/id/'.$id);
			} else {
					return view('diet_wastages.edit', [
							'diet_wastage'=>$diet_wastage,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_wastage = DietWastage::findOrFail($id);
		return view('diet_wastages.destroy', [
			'diet_wastage'=>$diet_wastage
			]);

	}
	public function destroy($id)
	{	
			DietWastage::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_wastages');
	}
	
	public function search(Request $request)
	{
			$diet_wastages = DB::table('diet_wastages as a')
					->leftjoin('diet_periods as b','b.period_code','=', 'a.period_code')
					->leftjoin('wards as c','c.ward_code','=', 'a.ward_code')
					->where('waste_date','like','%'.$request->search.'%')
					->orWhere('period_name', 'like','%'.$request->search.'%')
					->orWhere('ward_name', 'like','%'.$request->search.'%')
					->orderBy('waste_date')
					->paginate($this->paginateValue);

			return view('diet_wastages.index', [
					'diet_wastages'=>$diet_wastages,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_wastages = DB::table('diet_wastages as a')
					->leftjoin('diet_periods as b','b.period_code','=', 'a.period_code')
					->leftjoin('wards as c','c.ward_code','=', 'a.ward_code')
					->where('waste_id','=',$id)
					->paginate($this->paginateValue);

			return view('diet_wastages.index', [
					'diet_wastages'=>$diet_wastages
			]);
	}
}
