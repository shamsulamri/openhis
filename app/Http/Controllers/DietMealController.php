<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DietMeal;
use Log;
use DB;
use Session;


class DietMealController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$diet_meals = DB::table('diet_meals')
					->orderBy('meal_name')
					->paginate($this->paginateValue);
			return view('diet_meals.index', [
					'diet_meals'=>$diet_meals
			]);
	}

	public function create()
	{
			$diet_meal = new DietMeal();
			return view('diet_meals.create', [
					'diet_meal' => $diet_meal,
				
					]);
	}

	public function store(Request $request) 
	{
			$diet_meal = new DietMeal();
			$valid = $diet_meal->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$diet_meal = new DietMeal($request->all());
					$diet_meal->meal_code = $request->meal_code;
					$diet_meal->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/diet_meals/id/'.$diet_meal->meal_code);
			} else {
					return redirect('/diet_meals/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$diet_meal = DietMeal::findOrFail($id);
			return view('diet_meals.edit', [
					'diet_meal'=>$diet_meal,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$diet_meal = DietMeal::findOrFail($id);
			$diet_meal->fill($request->input());


			$valid = $diet_meal->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$diet_meal->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet_meals/id/'.$id);
			} else {
					return view('diet_meals.edit', [
							'diet_meal'=>$diet_meal,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$diet_meal = DietMeal::findOrFail($id);
		return view('diet_meals.destroy', [
			'diet_meal'=>$diet_meal
			]);

	}
	public function destroy($id)
	{	
			DietMeal::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_meals');
	}
	
	public function search(Request $request)
	{
			$diet_meals = DB::table('diet_meals')
					->where('meal_name','like','%'.$request->search.'%')
					->orWhere('meal_code', 'like','%'.$request->search.'%')
					->orderBy('meal_name')
					->paginate($this->paginateValue);

			return view('diet_meals.index', [
					'diet_meals'=>$diet_meals,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$diet_meals = DB::table('diet_meals')
					->where('meal_code','=',$id)
					->paginate($this->paginateValue);

			return view('diet_meals.index', [
					'diet_meals'=>$diet_meals
			]);
	}
}
