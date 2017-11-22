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

	public function store(Request $request) 
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

	public function edit($id) 
	{
			$medication_record = MedicationRecord::findOrFail($id);
			return view('medication_records.edit', [
					'medication_record'=>$medication_record,
				
					]);
	}

	public function update(Request $request, $id) 
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

	public function medicationAdministrationRecord($encounter_id=null) {
			$id = Session::get('consultation_id');
			$consultation = Consultation::find($id);
			if (empty($encounter_id)) {
				$encounter_id = $consultation->encounter_id;
			}
			$encounter = Encounter::find($encounter_id);

			$fields = ['product_name', 
					'a.product_code', 
					'cancel_id', 
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
					'name',
					];

			$drugs = DB::table('orders as a')
					->select($fields)
					->join('products as b','a.product_code','=','b.product_code')
					->leftjoin('order_cancellations as c', 'c.order_id', '=', 'a.order_id')
					->leftjoin('consultations as d', 'd.consultation_id', '=', 'a.consultation_id')
					->leftjoin('product_categories as e', 'e.category_code', '=', 'b.category_code')
					->leftjoin('order_drugs as f', 'f.order_id', '=', 'a.order_id')
					->leftjoin('drug_frequencies as g', 'g.frequency_code', '=', 'f.frequency_code')
					->leftjoin('users as h', 'h.id', '=', 'a.user_id')
					->where('a.post_id','>',0)
					->where('a.encounter_id','=',$encounter_id)
					->where('b.category_code','=','drugs')
					->orderBy('b.category_code')
					->orderBy('a.created_at', 'desc')
					->get();

			$mars = MedicationRecord::select('encounter_id', 'medication_slot', 'medication_datetime', 'username')
					->leftJoin('orders as b', 'b.order_id', '=', 'medication_records.order_id')
					->leftjoin('users as c', 'c.id', '=', 'medication_records.user_id')
					->where('b.encounter_id', '=', $encounter_id)
					->get();

			$mars =  $mars->keyBy('medication_slot');

			$mar_values = MedicationRecord::select('medication_datetime', 'medication_slot')
					->leftJoin('orders as b', 'b.order_id', '=', 'medication_records.order_id')
					->where('b.encounter_id', '=', $encounter_id);


			$start_date = $encounter->created_at;
			$los = DojoUtility::diffInDays($start_date);

			if ($los>5) {
				$start_date = DojoUtility::addDays(DojoUtility::dateReadFormat($start_date), $los-2);
			}

			return view('medication_records.mar', [
					'consultation'=>$consultation,
					'patient'=>$encounter->patient,
					'consultOption'=>'consultation',
					'admission'=>$encounter->admission,
					'encounter'=>$encounter,
					'drugs'=>$drugs,
					'mars'=>$mars,
					'mar_values'=>$mar_values,
					'start_date'=>$start_date,
					'entry_start'=>Carbon::now(),
					'entry_end'=>Carbon::now()->addDays(1),
					'order_helper'=>new OrderHelper(),
					]);
	}

	public function marRecord($order_id, $index, $slot)
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
}
