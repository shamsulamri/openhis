<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugRoute;
use Log;
use DB;
use Session;


class DrugRouteController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_routes = DB::table('drug_routes')
					->orderBy('route_name')
					->paginate($this->paginateValue);
			return view('drug_routes.index', [
					'drug_routes'=>$drug_routes
			]);
	}

	public function create()
	{
			$drug_route = new DrugRoute();
			return view('drug_routes.create', [
					'drug_route' => $drug_route,
				
					]);
	}

	public function store(Request $request) 
	{
			$drug_route = new DrugRoute();
			$valid = $drug_route->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_route = new DrugRoute($request->all());
					$drug_route->route_code = $request->route_code;
					$drug_route->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_routes/id/'.$drug_route->route_code);
			} else {
					return redirect('/drug_routes/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_route = DrugRoute::findOrFail($id);
			return view('drug_routes.edit', [
					'drug_route'=>$drug_route,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_route = DrugRoute::findOrFail($id);
			$drug_route->fill($request->input());


			$valid = $drug_route->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_route->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_routes/id/'.$id);
			} else {
					return view('drug_routes.edit', [
							'drug_route'=>$drug_route,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_route = DrugRoute::findOrFail($id);
		return view('drug_routes.destroy', [
			'drug_route'=>$drug_route
			]);

	}
	public function destroy($id)
	{	
			DrugRoute::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_routes');
	}
	
	public function search(Request $request)
	{
			$drug_routes = DB::table('drug_routes')
					->where('route_name','like','%'.$request->search.'%')
					->orWhere('route_code', 'like','%'.$request->search.'%')
					->orderBy('route_name')
					->paginate($this->paginateValue);

			return view('drug_routes.index', [
					'drug_routes'=>$drug_routes,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_routes = DB::table('drug_routes')
					->where('route_code','=',$id)
					->paginate($this->paginateValue);

			return view('drug_routes.index', [
					'drug_routes'=>$drug_routes
			]);
	}
}
