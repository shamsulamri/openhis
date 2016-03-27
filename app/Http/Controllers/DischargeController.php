<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Discharge;
use Log;
use DB;
use Session;
use App\DischargeType as Type;
use App\Consultation;
use Auth;
use App\Order;
use App\OrderPost;

class DischargeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$discharges = DB::table('discharges')
					->orderBy('type_code')
					->paginate($this->paginateValue);
			return view('discharges.index', [
					'discharges'=>$discharges
			]);
	}

	public function create($id)
	{
			$consultation = Consultation::find($id);
			$discharge = new Discharge();
			$discharge->encounter_id = $consultation->encounter_id;
			$discharge->consultation_id = $id;
			$discharge->discharge_date = date("d/m/Y");
			$discharge->user_id = Auth::user()->id;
			$discharge->type_code = 'home';

			if ($consultation->encounter->encounter_code != 'inpatient') {
					$orders = Order::where('consultation_id',$consultation->consultation_id)
							->where('post_id',0)
							->update(['order_is_discharge'=>1]);
			}

			$discharge_orders = DB::table('orders as a')
					->select(['a.product_code', 'product_name'])
					->leftJoin('products as b', 'b.product_code','=','a.product_code')
					->leftJoin('consultations as c', 'c.consultation_id','=','a.consultation_id')
					->leftJoin('encounters as d', 'd.encounter_id','=','c.encounter_id')
					->where('order_is_discharge',1)
					->where('d.encounter_id', $consultation->encounter_id)
					->get();

			return view('discharges.create', [
					'discharge' => $discharge,
					'type' => Type::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'consultation' => $consultation,
					'patient' => $consultation->encounter->patient,
					'consultOption' => 'consultation',
					'discharge_orders' => $discharge_orders
					]);
	}

	public function store(Request $request) 
	{
			$discharge = new Discharge();
			$valid = $discharge->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$discharge = new Discharge($request->all());
					$discharge->discharge_id = $request->discharge_id;
					$discharge->save();

					$consultation = Consultation::findOrFail($discharge->consultation_id);
					$consultation->consultation_status = 1;
					$consultation->save();

					$post = new OrderPost();
					$post->consultation_id = $consultation->consultation_id;
					$post->save();
					
					Order::where('consultation_id','=',$consultation->consultation_id)
							->where('post_id','=',0)
							->update(['post_id'=>$post->post_id, 'order_is_discharge'=>1]);

					Session::flash('message', 'Patient has been discharged.');
					return redirect('/patient_lists');
			} else {
					return redirect('/discharges/create/'.$request->consultation_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$discharge = Discharge::findOrFail($id);
			$consultation = $discharge->consultation;

			$discharge_orders = DB::table('orders as a')
					->select(['a.product_code', 'product_name'])
					->leftJoin('products as b', 'b.product_code','=','a.product_code')
					->leftJoin('consultations as c', 'c.consultation_id','=','a.consultation_id')
					->leftJoin('encounters as d', 'd.encounter_id','=','c.encounter_id')
					->where('order_is_discharge',1)
					->where('d.encounter_id', $consultation->encounter_id)
					->get();
			return view('discharges.edit', [
					'discharge'=>$discharge,
					'type' => Type::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'discharge_orders' => $discharge_orders,
					'consultation' => $consultation,
					]);
	}

	public function update(Request $request, $id) 
	{
			$discharge = Discharge::findOrFail($id);
			$discharge->fill($request->input());


			$valid = $discharge->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$discharge->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultations');
			} else {
					return view('discharges.edit', [
							'discharge'=>$discharge,
					'type' => Type::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$discharge = Discharge::findOrFail($id);
		return view('discharges.destroy', [
			'discharge'=>$discharge
			]);

	}
	public function destroy($id)
	{	
			Discharge::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/discharges');
	}
	
	public function search(Request $request)
	{
			$discharges = DB::table('discharges')
					->where('type_code','like','%'.$request->search.'%')
					->orWhere('discharge_id', 'like','%'.$request->search.'%')
					->orderBy('type_code')
					->paginate($this->paginateValue);

			return view('discharges.index', [
					'discharges'=>$discharges,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$discharges = DB::table('discharges')
					->where('discharge_id','=',$id)
					->paginate($this->paginateValue);

			return view('discharges.index', [
					'discharges'=>$discharges
			]);
	}

	public function ward($id)
	{
			return "ward";
	}
}
