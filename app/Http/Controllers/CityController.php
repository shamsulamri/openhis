<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\City;
use Log;
use DB;
use Session;
use App\State;


class CityController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$cities = DB::table('ref_cities')
					->orderBy('city_name')
					->paginate($this->paginateValue);
			return view('cities.index', [
					'cities'=>$cities
			]);
	}

	public function create()
	{
			$city = new City();
			return view('cities.create', [
					'city' => $city,
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$city = new City();
			$valid = $city->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$city = new City($request->all());
					$city->city_code = $request->city_code;
					$city->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/cities/id/'.$city->city_code);
			} else {
					return redirect('/cities/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$city = City::findOrFail($id);
			return view('cities.edit', [
					'city'=>$city,
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$city = City::findOrFail($id);
			$city->fill($request->input());


			$valid = $city->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$city->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/cities/id/'.$id);
			} else {
					return view('cities.edit', [
							'city'=>$city,
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$city = City::findOrFail($id);
		return view('cities.destroy', [
			'city'=>$city
			]);

	}
	public function destroy($id)
	{	
			City::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/cities');
	}
	
	public function search(Request $request)
	{
			$cities = DB::table('ref_cities')
					->where('city_name','like','%'.$request->search.'%')
					->orWhere('city_code', 'like','%'.$request->search.'%')
					->orderBy('city_name')
					->paginate($this->paginateValue);

			return view('cities.index', [
					'cities'=>$cities,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$cities = DB::table('ref_cities')
					->where('city_code','=',$id)
					->paginate($this->paginateValue);

			return view('cities.index', [
					'cities'=>$cities
			]);
	}
}
