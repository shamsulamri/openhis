<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderRoute;
use Log;
use DB;
use Session;
use App\Encounter;
use App\Category;
use App\Store;


class OrderRouteController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$order_routes = DB::table('order_routes')
					->orderBy('encounter_code')
					->paginate($this->paginateValue);
			return view('order_routes.index', [
					'order_routes'=>$order_routes
			]);
	}

	public function create()
	{
			$order_route = new OrderRoute();
			return view('order_routes.create', [
					'order_route' => $order_route,
					'encounter' => Encounter::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$order_route = new OrderRoute();
			$valid = $order_route->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_route = new OrderRoute($request->all());
					$order_route->route_id = $request->route_id;
					$order_route->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/order_routes/id/'.$order_route->route_id);
			} else {
					return redirect('/order_routes/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_route = OrderRoute::findOrFail($id);
			return view('order_routes.edit', [
					'order_route'=>$order_route,
					'encounter' => Encounter::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_route = OrderRoute::findOrFail($id);
			$order_route->fill($request->input());


			$valid = $order_route->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_route->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_routes/id/'.$id);
			} else {
					return view('order_routes.edit', [
							'order_route'=>$order_route,
					'encounter' => Encounter::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_route = OrderRoute::findOrFail($id);
		return view('order_routes.destroy', [
			'order_route'=>$order_route
			]);

	}
	public function destroy($id)
	{	
			OrderRoute::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_routes');
	}
	
	public function search(Request $request)
	{
			$order_routes = DB::table('order_routes')
					->where('encounter_code','like','%'.$request->search.'%')
					->orWhere('route_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_code')
					->paginate($this->paginateValue);

			return view('order_routes.index', [
					'order_routes'=>$order_routes,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$order_routes = DB::table('order_routes')
					->where('route_id','=',$id)
					->paginate($this->paginateValue);

			return view('order_routes.index', [
					'order_routes'=>$order_routes
			]);
	}
}
