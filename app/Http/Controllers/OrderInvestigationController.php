<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderInvestigation;
use Log;
use DB;
use Session;
use App\Urgency;
use App\Period;
use App\Frequency;


class OrderInvestigationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$order_investigations = DB::table('order_investigations')
					->orderBy('order_id')
					->paginate($this->paginateValue);
			return view('order_investigations.index', [
					'order_investigations'=>$order_investigations
			]);
	}

	public function create()
	{
			$order_investigation = new OrderInvestigation();
			return view('order_investigations.create', [
					'order_investigation' => $order_investigation,
					'urgency' => Urgency::all()->sortBy('urgency_name')->lists('urgency_name', 'urgency_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$order_investigation = new OrderInvestigation();
			$valid = $order_investigation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_investigation = new OrderInvestigation($request->all());
					$order_investigation->id = $request->id;
					$order_investigation->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/order_investigations/id/'.$order_investigation->id);
			} else {
					return redirect('/order_investigations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_investigation = OrderInvestigation::findOrFail($id);
			return view('order_investigations.edit', [
					'order_investigation'=>$order_investigation,
					'urgency' => Urgency::all()->sortBy('urgency_name')->lists('urgency_name', 'urgency_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_investigation = OrderInvestigation::findOrFail($id);
			$order_investigation->fill($request->input());

			$order_investigation->investigation_recur = $request->investigation_recur ?: 0;

			$valid = $order_investigation->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_investigation->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_investigations/id/'.$id);
			} else {
					return view('order_investigations.edit', [
							'order_investigation'=>$order_investigation,
					'urgency' => Urgency::all()->sortBy('urgency_name')->lists('urgency_name', 'urgency_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_investigation = OrderInvestigation::findOrFail($id);
		return view('order_investigations.destroy', [
			'order_investigation'=>$order_investigation
			]);

	}
	public function destroy($id)
	{	
			OrderInvestigation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_investigations');
	}
	
	public function search(Request $request)
	{
			$order_investigations = DB::table('order_investigations')
					->where('order_id','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
					->orderBy('order_id')
					->paginate($this->paginateValue);

			return view('order_investigations.index', [
					'order_investigations'=>$order_investigations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$order_investigations = DB::table('order_investigations')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('order_investigations.index', [
					'order_investigations'=>$order_investigations
			]);
	}
}
