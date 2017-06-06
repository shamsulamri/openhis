<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietTherapeutic;
use Log;
use DB;
use Session;


class DietTherapeuticController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_therapeutics = DB::table('diet_therapeutics')
					->orderBy('therapeutic_code')
					->paginate($this->paginateValue);
			return view('diet_therapeutics.index', [
					'diet_therapeutics'=>$diet_therapeutics
			]);
	}

	public function create()
	{
			$diet_therapeutic = new DietTherapeutic();
			return view('diet_therapeutics.create', [
					'diet_therapeutic' => $diet_therapeutic,
				
					]);
	}

	public function store(Request $request) 
	{
			$diet_therapeutic = new DietTherapeutic();
			$valid = $diet_therapeutic->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_therapeutic = new DietTherapeutic($request->all());
					$diet_therapeutic->therapeutic_code = $request->therapeutic_code;
					$diet_therapeutic->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_therapeutics/id/'.$diet_therapeutic->therapeutic_code);
			} else {
					return redirect('/diet_therapeutics/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_therapeutic = DietTherapeutic::findOrFail($id);
			return view('diet_therapeutics.edit', [
					'diet_therapeutic'=>$diet_therapeutic,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_therapeutic = DietTherapeutic::findOrFail($id);
			$diet_therapeutic->fill($request->input());


			$valid = $diet_therapeutic->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_therapeutic->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_therapeutics/id/'.$id);
			} else {
					return view('diet_therapeutics.edit', [
							'diet_therapeutic'=>$diet_therapeutic,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_therapeutic = DietTherapeutic::findOrFail($id);
		return view('diet_therapeutics.destroy', [
			'diet_therapeutic'=>$diet_therapeutic
			]);

	}
	public function destroy($id)
	{	
			DietTherapeutic::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_therapeutics');
	}
	
	public function search(Request $request)
	{
			$diet_therapeutics = DB::table('diet_therapeutics')
					->where('therapeutic_code','like','%'.$request->search.'%')
					->orWhere('therapeutic_code', 'like','%'.$request->search.'%')
					->orderBy('therapeutic_code')
					->paginate($this->paginateValue);

			return view('diet_therapeutics.index', [
					'diet_therapeutics'=>$diet_therapeutics,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_therapeutics = DB::table('diet_therapeutics')
					->where('therapeutic_code','=',$id)
					->paginate($this->paginateValue);

			return view('diet_therapeutics.index', [
					'diet_therapeutics'=>$diet_therapeutics
			]);
	}
}
