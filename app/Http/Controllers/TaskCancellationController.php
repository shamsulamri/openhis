<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderCancellation as TaskCancellation;
use Log;
use DB;
use Session;
use Auth;

class TaskCancellationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$task_cancellations = DB::table('order_cancellations')
					->orderBy('order_id')
					->paginate($this->paginateValue);
			return view('task_cancellations.index', [
					'task_cancellations'=>$task_cancellations
			]);
	}

	public function create(Request $request, $order_id)
	{
			$task_cancellation = new TaskCancellation();
			$task_cancellation->order_id = $order_id;

			return view('task_cancellations.create', [
					'task_cancellation' => $task_cancellation,
					'consultation' => $task_cancellation->order->consultation,	
					'patient' => $task_cancellation->order->consultation->encounter->patient,	
					'source' => $request->source,
					]);
	}

	public function store(Request $request) 
	{
			$task_cancellation = new TaskCancellation();
			$valid = $task_cancellation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$task_cancellation = new TaskCancellation($request->all());
					$task_cancellation->cancel_id = $request->cancel_id;
					$task_cancellation->user_id = Auth::user()->id;
					$task_cancellation->save();
					Session::flash('message', 'The selected order has been cancel.');
					if ($request->source=='nurse') {
							return redirect('/admission_tasks');
					} else {
							return redirect('/order_tasks/task/'.Session::get('encounter_id').'/'.$task_cancellation->order->product->category->location_code);
					}
			} else {
					return redirect('/task_cancellations/create/'.$request->order_id.'?source='.$request->source)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$task_cancellation = TaskCancellation::findOrFail($id);
			return view('task_cancellations.edit', [
					'task_cancellation'=>$task_cancellation,
					]);
	}

	public function update(Request $request, $id) 
	{
			$task_cancellation = TaskCancellation::findOrFail($id);
			$task_cancellation->fill($request->input());


			$valid = $task_cancellation->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$task_cancellation->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/task_cancellations/id/'.$id);
			} else {
					return view('task_cancellations.edit', [
							'task_cancellation'=>$task_cancellation,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$task_cancellation = TaskCancellation::findOrFail($id);
		return view('task_cancellations.destroy', [
			'task_cancellation'=>$task_cancellation
			]);

	}
	public function destroy($id)
	{	
			TaskCancellation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/task_cancellations');
	}
	
	public function search(Request $request)
	{
			$task_cancellations = DB::table('order_cancellations')
					->where('order_id','like','%'.$request->search.'%')
					->orWhere('cancel_id', 'like','%'.$request->search.'%')
					->orderBy('order_id')
					->paginate($this->paginateValue);

			return view('task_cancellations.index', [
					'task_cancellations'=>$task_cancellations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$task_cancellations = DB::table('order_cancellations')
					->where('cancel_id','=',$id)
					->paginate($this->paginateValue);

			return view('task_cancellations.index', [
					'task_cancellations'=>$task_cancellations
			]);
	}
}
