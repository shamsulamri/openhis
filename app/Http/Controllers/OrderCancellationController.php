<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderCancellation;
use Log;
use DB;
use Session;
use App\Consultation;
use Auth;

class OrderCancellationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$order_cancellations = DB::table('order_cancellations')
					->orderBy('cancel_reason')
					->paginate($this->paginateValue);
			return view('order_cancellations.index', [
					'order_cancellations'=>$order_cancellations,
			]);
	}

	public function create($id)
	{
			$order_cancellation = new OrderCancellation();
			$order_cancellation->order_id = $id;

			return view('order_cancellations.create', [
					'order_cancellation' => $order_cancellation,
					'consultation' => $order_cancellation->order->consultation,
					'patient' => $order_cancellation->order->consultation->encounter->patient,
					'tab' => 'order',			
					'consultOption' => 'consultation',			
					]);
	}

	public function store(Request $request) 
	{
			$order_cancellation = new OrderCancellation();
			$valid = $order_cancellation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_cancellation = new OrderCancellation($request->all());
					$order_cancellation->cancel_id = $request->cancel_id;
					$order_cancellation->user_id = Auth::user()->id;
					$order_cancellation->save();
					Session::flash('message', 'Record successfully created.');
					//return redirect('/orders/'.$order_cancellation->order->consultation_id);
					return redirect('/orders');
			} else {
					return redirect('/order_cancellations/create/'.$request->order_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_cancellation = OrderCancellation::findOrFail($id);
			return view('order_cancellations.edit', [
					'order_cancellation'=>$order_cancellation,
					'consultation' => $order_cancellation->order->consultation,
					'tab' => 'order',			
					'consultOption' => 'consultation',			
					]);
	}

	public function show($id) 
	{
			$order_cancellation = OrderCancellation::findOrFail($id);
			return view('order_cancellations.show', [
					'order_cancellation'=>$order_cancellation,
					'consultation' => $order_cancellation->order->consultation,
					'patient' => $order_cancellation->order->consultation->encounter->patient,
					'tab' => 'order',			
					'consultOption' => 'consultation',			
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_cancellation = OrderCancellation::findOrFail($id);
			$order_cancellation->fill($request->input());


			$valid = $order_cancellation->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_cancellation->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_cancellations/id/'.$id);
			} else {
					return view('order_cancellations.edit', [
							'order_cancellation'=>$order_cancellation,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_cancellation = OrderCancellation::findOrFail($id);
		return view('order_cancellations.destroy', [
			'order_cancellation'=>$order_cancellation
			]);

	}
	public function destroy($id)
	{	
			OrderCancellation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_cancellations');
	}
	
	public function search(Request $request)
	{
			$order_cancellations = DB::table('order_cancellations')
					->where('cancel_reason','like','%'.$request->search.'%')
					->orWhere('cancel_id', 'like','%'.$request->search.'%')
					->orderBy('cancel_reason')
					->paginate($this->paginateValue);

			return view('order_cancellations.index', [
					'order_cancellations'=>$order_cancellations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$order_cancellations = DB::table('order_cancellations')
					->where('cancel_id','=',$id)
					->paginate($this->paginateValue);

			return view('order_cancellations.index', [
					'order_cancellations'=>$order_cancellations
			]);
	}
}
