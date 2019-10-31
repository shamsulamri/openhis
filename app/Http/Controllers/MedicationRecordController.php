<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MedicationRecord;
use Log;
use DB;
use Session;
use App\Consultation;
use Carbon\Carbon;
use App\DojoUtility;
use Auth;
use App\OrderHelper;
use App\Encounter;
use App\Order;

class MedicationRecordController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$medication_records = DB::table('medication_records')
					->orderBy('order_id')
					->paginate($this->paginateValue);
			return view('medication_records.index', [
					'medication_records'=>$medication_records
			]);
	}

	public function create()
	{
			$medication_record = new MedicationRecord();
			return view('medication_records.create', [
					'medication_record' => $medication_record,
				
					]);
	}

	public function store2(Request $request) 
	{
			$medication_record = new MedicationRecord();
			$valid = $medication_record->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$medication_record = new MedicationRecord($request->all());
					$medication_record->medication_id = $request->medication_id;
					$medication_record->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/medication_records/id/'.$medication_record->medication_id);
			} else {
					return redirect('/medication_records/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function store(Request $request) 
	{
			$index = 0;
			if (!empty($request->index)) $index = $request->index;

			$mar = new MedicationRecord();
			$mar->order_id = $request->order_id;
			$mar->medication_index = $index;
			$mar->medication_slot = $request->order_id.'-'.$index.'-'.$request->slot;
			$mar->user_id = Auth::user()->id;

			$datetime = $request->medication_date.' '.$request->medication_time;
			$datetime = DojoUtility::datetimeWriteFormat($datetime);
			$mar->medication_datetime = $datetime;
			$mar->medication_description = $request->medication_description;
			$mar->medication_fail = $request->medication_fail ?: 0;
			$mar->save();

			$order = Order::find($mar->order_id);
			$helper = new OrderHelper();
			$helper->marUnitCount($mar->order_id);

			if ($order->orderDrug->frequency_code == 'STAT') {
				$order->order_completed = 1;
				$order->save();
			}

			return redirect('/medication_record/mar/'.$mar->order->encounter_id);
	}


	public function edit($id) 
	{
			$medication_record = MedicationRecord::findOrFail($id);
			return view('medication_records.edit', [
					'medication_record'=>$medication_record,
			]);
	}

	public function datetime($id) 
	{
			$medication_record = MedicationRecord::findOrFail($id);
			return view('medication_records.datetime', [
					'medication_record'=>$medication_record,
					'patient'=>$medication_record->order->consultation->encounter->patient,
					'order'=>$medication_record->order,
			]);
	}

	public function update(Request $request, $id) 
	{
			$medication_record = MedicationRecord::findOrFail($id);
			$datetime = $request->medication_date.' '.$request->medication_time;
			$datetime = DojoUtility::datetimeWriteFormat($datetime);
			$medication_record->medication_datetime = $datetime;
			$medication_record->medication_description = $request->medication_description;
			$medication_record->medication_fail = $request->medication_fail ?: 0;
			$medication_record->save();

			$helper = new OrderHelper();
			$helper->marUnitCount($medication_record->order_id);

			return redirect('/medication_record/mar/'.$medication_record->order->encounter_id);
	}

	public function update2(Request $request, $id) 
	{
			$medication_record = MedicationRecord::findOrFail($id);
			$medication_record->fill($request->input());


			$valid = $medication_record->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$medication_record->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/medication_records/id/'.$id);
			} else {
					return view('medication_records.edit', [
							'medication_record'=>$medication_record,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$medication_record = MedicationRecord::findOrFail($id);
		return view('medication_records.destroy', [
			'medication_record'=>$medication_record
			]);

	}
	public function destroy($id)
	{	
			MedicationRecord::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/medication_records');
	}
	
	public function search(Request $request)
	{
			$medication_records = DB::table('medication_records')
					->where('order_id','like','%'.$request->search.'%')
					->orWhere('medication_id', 'like','%'.$request->search.'%')
					->orderBy('order_id')
					->paginate($this->paginateValue);

			return view('medication_records.index', [
					'medication_records'=>$medication_records,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$medication_records = DB::table('medication_records')
					->where('medication_id','=',$id)
					->paginate($this->paginateValue);

			return view('medication_records.index', [
					'medication_records'=>$medication_records
			]);
	}

	public function medicationAdministrationRecord(Request $request, $encounter_id=null) {
			$id = Session::get('consultation_id');
			$consultation = Consultation::where('consultation_id', $id)
					->first();

			if (empty($encounter_id)) {
					$encounter_id = $consultation->encounter_id;
			}

			$encounter = Encounter::find($encounter_id);

			$fields = ['product_name', 
					'a.product_code', 
					'stop_id', 
					'a.order_id', 
					'a.user_id', 
					'order_quantity_request',
					'post_id', 
					'd.created_at',
					'order_is_discharge',
					'order_completed',
					'order_report',
					'category_name',
					'product_edit_price',
					'frequency_mar',
					'h.name',
					'i.name as stop_by',
					'c.created_at as stop_date',
					'order_quantity_supply',
					];

			$drugs = DB::table('orders as a')
					->select($fields)
					->join('products as b','a.product_code','=','b.product_code')
					->leftjoin('order_stops as c', 'c.order_id', '=', 'a.order_id')
					->leftjoin('consultations as d', 'd.consultation_id', '=', 'a.consultation_id')
					->leftjoin('product_categories as e', 'e.category_code', '=', 'b.category_code')
					->leftjoin('order_drugs as f', 'f.order_id', '=', 'a.order_id')
					->leftjoin('drug_frequencies as g', 'g.frequency_code', '=', 'f.frequency_code')
					->leftjoin('users as h', 'h.id', '=', 'a.user_id')
					->leftjoin('users as i', 'i.id', '=', 'c.user_id')
					->where('a.post_id','>',0)
					->where('a.encounter_id','=',$encounter_id)
					->where('order_is_discharge','<>',1)
					->where('product_local_store','==',0)
					->where('product_drop_charge','==',0)
					->whereNotNull('f.order_id')
					->orderBy('b.category_code')
					->orderBy('b.category_code')
					->orderBy('stop_id', 'asc')
					->orderBy('product_name')
					->get();
					//->orderBy('a.created_at', 'desc')

			$mars = MedicationRecord::select('medication_id','encounter_id', 'medication_slot', 'medication_datetime', 'username', 'name', 'medication_fail')
					->leftJoin('orders as b', 'b.order_id', '=', 'medication_records.order_id')
					->leftjoin('users as c', 'c.id', '=', 'medication_records.user_id')
					->where('b.encounter_id', '=', $encounter_id)
					->get();

			$mars =  $mars->keyBy('medication_slot');


			$verifications = MedicationRecord::select('medication_id','encounter_id', 'medication_slot', 'medication_datetime', 'username', 'name')
					->leftJoin('orders as b', 'b.order_id', '=', 'medication_records.order_id')
					->leftjoin('users as c', 'c.id', '=', 'medication_records.verify_id')
					->where('b.encounter_id', '=', $encounter_id)
					->whereNotNull('verify_id')
					->get();

			$verifications =  $verifications->keyBy('medication_slot');

			$mar_values = MedicationRecord::select('medication_datetime', 'medication_slot')
					->leftJoin('orders as b', 'b.order_id', '=', 'medication_records.order_id')
					->where('b.encounter_id', '=', $encounter_id);


			$start_date = $encounter->created_at;
			$los = DojoUtility::diffInDays($start_date);

			if ($los>5) {
				$start_date = DojoUtility::addDays(DojoUtility::dateReadFormat($start_date), $los-2);
			}

			if ($request->view == 1) {
					// Pharmacy view MAR
					$consultation = null;
			}

			return view('medication_records.mar', [
					'consultation'=>$consultation,
					'patient'=>$encounter->patient,
					'consultOption'=>'consultation',
					'admission'=>$encounter->admission,
					'encounter'=>$encounter,
					'drugs'=>$drugs,
					'mars'=>$mars,
					'verifications'=>$verifications,
					'mar_values'=>$mar_values,
					'start_date'=>$start_date,
					'entry_start'=>Carbon::now(),
					'entry_end'=>Carbon::now()->addDays(1),
					'order_helper'=>new OrderHelper(),
					'view'=>$request->view==1?true:false,
					]);
	}

	public function marRecord($order_id, $index, $slot)
	{
			$order = Order::find($order_id);
			$mar = new MedicationRecord();
			$mar->order_id = $order_id;
			$mar->medication_index = $index;
			$mar->medication_slot = $order_id.'-'.$index.'-'.$slot;
			$mar->user_id = Auth::user()->id;
			$now = DojoUtility::now();
			$mar->medication_datetime = DojoUtility::dateTimeWriteFormat($now);

			return view('medication_records.create', [
					'medication_record'=>$mar,
					'patient'=>$order->consultation->encounter->patient,
					'order'=>$order,
					'index'=>$index,
					'slot'=>$slot,
			]);
	}

	public function marRecord2($order_id, $index, $slot)
	{
			$mar = new MedicationRecord();
			$mar->order_id = $order_id;
			$mar->medication_index = $index;
			$mar->medication_slot = $order_id.'-'.$index.'-'.$slot;
			$mar->user_id = Auth::user()->id;
			$now = DojoUtility::now();
			$mar->medication_datetime = DojoUtility::dateTimeWriteFormat($now);
			$mar->save();
			
			return redirect('/medication_record/mar/'.$mar->order->encounter_id);
	}

	public function marVerify($order_id, $index, $slot)
	{
			$medication_slot = $order_id.'-'.$index.'-'.$slot;
			$mar = MedicationRecord::where('order_id','=',$order_id)
						->where('medication_index', '=', $index)
						->where('medication_slot', '=', $medication_slot)
						->first();

			$mar->verify_id = Auth::user()->id;
			$mar->save();
			
			return redirect('/medication_record/mar/'.$mar->order->encounter_id);
	}
}
