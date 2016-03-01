<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Diet;
use Log;
use DB;
use Session;


class DietController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diets = DB::table('diets')
					->orderBy('diet_name')
					->paginate($this->paginateValue);
			return view('diets.index', [
					'diets'=>$diets
			]);
	}

	public function create()
	{
			$diet = new Diet();
			return view('diets.create', [
					'diet' => $diet,
				
					]);
	}

	public function store(Request $request) 
	{
			$diet = new Diet();
			$valid = $diet->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet = new Diet($request->all());
					$diet->diet_code = $request->diet_code;
					$diet->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diets/id/'.$diet->diet_code);
			} else {
					return redirect('/diets/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet = Diet::findOrFail($id);
			return view('diets.edit', [
					'diet'=>$diet,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet = Diet::findOrFail($id);
			$diet->fill($request->input());


			$valid = $diet->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diets/id/'.$id);
			} else {
					return view('diets.edit', [
							'diet'=>$diet,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet = Diet::findOrFail($id);
		return view('diets.destroy', [
			'diet'=>$diet
			]);

	}
	public function destroy($id)
	{	
			Diet::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diets');
	}
	
	public function search(Request $request)
	{
			$diets = DB::table('diets')
					->where('diet_name','like','%'.$request->search.'%')
					->orWhere('diet_code', 'like','%'.$request->search.'%')
					->orderBy('diet_name')
					->paginate($this->paginateValue);

			return view('diets.index', [
					'diets'=>$diets,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diets = DB::table('diets')
					->where('diet_code','=',$id)
					->paginate($this->paginateValue);

			return view('diets.index', [
					'diets'=>$diets
			]);
	}
}
