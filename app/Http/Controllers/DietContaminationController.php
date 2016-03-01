<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietContamination;
use Log;
use DB;
use Session;


class DietContaminationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_contaminations = DB::table('diet_contaminations')
					->orderBy('contamination_name')
					->paginate($this->paginateValue);
			return view('diet_contaminations.index', [
					'diet_contaminations'=>$diet_contaminations
			]);
	}

	public function create()
	{
			$diet_contamination = new DietContamination();
			return view('diet_contaminations.create', [
					'diet_contamination' => $diet_contamination,
				
					]);
	}

	public function store(Request $request) 
	{
			$diet_contamination = new DietContamination();
			$valid = $diet_contamination->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_contamination = new DietContamination($request->all());
					$diet_contamination->contamination_code = $request->contamination_code;
					$diet_contamination->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_contaminations/id/'.$diet_contamination->contamination_code);
			} else {
					return redirect('/diet_contaminations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_contamination = DietContamination::findOrFail($id);
			return view('diet_contaminations.edit', [
					'diet_contamination'=>$diet_contamination,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_contamination = DietContamination::findOrFail($id);
			$diet_contamination->fill($request->input());


			$valid = $diet_contamination->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_contamination->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_contaminations/id/'.$id);
			} else {
					return view('diet_contaminations.edit', [
							'diet_contamination'=>$diet_contamination,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_contamination = DietContamination::findOrFail($id);
		return view('diet_contaminations.destroy', [
			'diet_contamination'=>$diet_contamination
			]);

	}
	public function destroy($id)
	{	
			DietContamination::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_contaminations');
	}
	
	public function search(Request $request)
	{
			$diet_contaminations = DB::table('diet_contaminations')
					->where('contamination_name','like','%'.$request->search.'%')
					->orWhere('contamination_code', 'like','%'.$request->search.'%')
					->orderBy('contamination_name')
					->paginate($this->paginateValue);

			return view('diet_contaminations.index', [
					'diet_contaminations'=>$diet_contaminations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_contaminations = DB::table('diet_contaminations')
					->where('contamination_code','=',$id)
					->paginate($this->paginateValue);

			return view('diet_contaminations.index', [
					'diet_contaminations'=>$diet_contaminations
			]);
	}
}
