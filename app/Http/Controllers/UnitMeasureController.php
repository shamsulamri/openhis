<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UnitMeasure;
use Log;
use DB;
use Session;


class UnitMeasureController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$unit_measures = DB::table('ref_unit_measures')
					->orderBy('unit_name')
					->paginate($this->paginateValue);
			return view('unit_measures.index', [
					'unit_measures'=>$unit_measures
			]);
	}

	public function create()
	{
			$unit_measure = new UnitMeasure();
			return view('unit_measures.create', [
					'unit_measure' => $unit_measure,
				
					]);
	}

	public function store(Request $request) 
	{
			$unit_measure = new UnitMeasure();
			$valid = $unit_measure->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$unit_measure = new UnitMeasure($request->all());
					$unit_measure->unit_code = $request->unit_code;
					$unit_measure->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/unit_measures/id/'.$unit_measure->unit_code);
			} else {
					return redirect('/unit_measures/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$unit_measure = UnitMeasure::findOrFail($id);
			return view('unit_measures.edit', [
					'unit_measure'=>$unit_measure,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$unit_measure = UnitMeasure::findOrFail($id);
			$unit_measure->fill($request->input());

			$unit_measure->unit_is_decimal = $request->unit_is_decimal ?: 0;
			$unit_measure->unit_drug = $request->unit_drug ?: 0;

			$valid = $unit_measure->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$unit_measure->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/unit_measures/id/'.$id);
			} else {
					return view('unit_measures.edit', [
							'unit_measure'=>$unit_measure,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$unit_measure = UnitMeasure::findOrFail($id);
		return view('unit_measures.destroy', [
			'unit_measure'=>$unit_measure
			]);

	}
	public function destroy($id)
	{	
			UnitMeasure::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/unit_measures');
	}
	
	public function search(Request $request)
	{
			$unit_measures = DB::table('ref_unit_measures')
					->where('unit_name','like','%'.$request->search.'%')
					->orWhere('unit_code', 'like','%'.$request->search.'%')
					->orderBy('unit_name')
					->paginate($this->paginateValue);

			return view('unit_measures.index', [
					'unit_measures'=>$unit_measures,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$unit_measures = DB::table('ref_unit_measures')
					->where('unit_code','=',$id)
					->paginate($this->paginateValue);

			return view('unit_measures.index', [
					'unit_measures'=>$unit_measures
			]);
	}
}
