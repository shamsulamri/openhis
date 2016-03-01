<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietEnteral;
use Log;
use DB;
use Session;


class DietEnteralController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_enterals = DB::table('diet_enterals')
					->orderBy('enteral_name')
					->paginate($this->paginateValue);
			return view('diet_enterals.index', [
					'diet_enterals'=>$diet_enterals
			]);
	}

	public function create()
	{
			$diet_enteral = new DietEnteral();
			return view('diet_enterals.create', [
					'diet_enteral' => $diet_enteral,
				
					]);
	}

	public function store(Request $request) 
	{
			$diet_enteral = new DietEnteral();
			$valid = $diet_enteral->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_enteral = new DietEnteral($request->all());
					$diet_enteral->enteral_code = $request->enteral_code;
					$diet_enteral->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_enterals/id/'.$diet_enteral->enteral_code);
			} else {
					return redirect('/diet_enterals/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_enteral = DietEnteral::findOrFail($id);
			return view('diet_enterals.edit', [
					'diet_enteral'=>$diet_enteral,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_enteral = DietEnteral::findOrFail($id);
			$diet_enteral->fill($request->input());


			$valid = $diet_enteral->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_enteral->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_enterals/id/'.$id);
			} else {
					return view('diet_enterals.edit', [
							'diet_enteral'=>$diet_enteral,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_enteral = DietEnteral::findOrFail($id);
		return view('diet_enterals.destroy', [
			'diet_enteral'=>$diet_enteral
			]);

	}
	public function destroy($id)
	{	
			DietEnteral::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_enterals');
	}
	
	public function search(Request $request)
	{
			$diet_enterals = DB::table('diet_enterals')
					->where('enteral_name','like','%'.$request->search.'%')
					->orWhere('enteral_code', 'like','%'.$request->search.'%')
					->orderBy('enteral_name')
					->paginate($this->paginateValue);

			return view('diet_enterals.index', [
					'diet_enterals'=>$diet_enterals,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_enterals = DB::table('diet_enterals')
					->where('enteral_code','=',$id)
					->paginate($this->paginateValue);

			return view('diet_enterals.index', [
					'diet_enterals'=>$diet_enterals
			]);
	}
}
