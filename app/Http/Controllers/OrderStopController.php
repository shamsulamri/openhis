<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderStop;
use Log;
use DB;
use Session;
use Auth;
use App\Order;
use App\DojoUtility;
use App\Encounter;

class OrderStopController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$order_stops = DB::table('order_stops')
					->orderBy('order_id')
					->paginate($this->paginateValue);
			return view('order_stops.index', [
					'order_stops'=>$order_stops
			]);
	}

	public function create($id)
	{
			$order_stop = new OrderStop();
			$order_stop->order_id = $id;
			$order_stop->user_id = Auth::user()->id;

			$order = Order::find($id);
			$encounter = Encounter::find($order->encounter_id);
			return view('order_stops.create', [
					'order_stop' => $order_stop,
					'encounter' => $encounter, 
					'patient' => $encounter->patient, 
					]);
	}

	public function store(Request $request) 
	{
			$order_stop = new OrderStop();
			$valid = $order_stop->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order = Order::find($request->order_id);
					$units = DojoUtility::diffInMinutes($order->created_at);

					$order->order_total = round($units/60);
					$order->order_completed = 1;
					$order->save();

					$order_stop = new OrderStop($request->all());
					$order_stop->stop_id = $request->stop_id;
					$order_stop->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('admission_tasks');
					//return redirect('/order_stops/id/'.$order_stop->stop_id);
			} else {
					return redirect('/order_stops/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_stop = OrderStop::findOrFail($id);
			return view('order_stops.edit', [
					'order_stop'=>$order_stop,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_stop = OrderStop::findOrFail($id);
			$order_stop->fill($request->input());


			$valid = $order_stop->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_stop->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_stops/id/'.$id);
			} else {
					return view('order_stops.edit', [
							'order_stop'=>$order_stop,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_stop = OrderStop::findOrFail($id);
		return view('order_stops.destroy', [
			'order_stop'=>$order_stop
			]);

	}
	public function destroy($id)
	{	
			OrderStop::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_stops');
	}
	
	public function search(Request $request)
	{
			$order_stops = DB::table('order_stops')
					->where('order_id','like','%'.$request->search.'%')
					->orWhere('stop_id', 'like','%'.$request->search.'%')
					->orderBy('order_id')
					->paginate($this->paginateValue);

			return view('order_stops.index', [
					'order_stops'=>$order_stops,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$order_stops = DB::table('order_stops')
					->where('stop_id','=',$id)
					->paginate($this->paginateValue);

			return view('order_stops.index', [
					'order_stops'=>$order_stops
			]);
	}
}
