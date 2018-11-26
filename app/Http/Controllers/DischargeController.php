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
use Carbon\Carbon;
use App\MedicalCertificate;
use App\DojoUtility;
use App\DischargeType;
use App\EncounterType;
use App\DischargeHelper;
use App\Encounter;
use App\PatientFlag;
use App\OrderHelper;
use App\BedCharge;
use App\PatientType;
use App\BillHelper;
use App\Room;

class DischargeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			/**
			$discharges = DB::table('discharges as a')
					->select('patient_mrn', 'b.encounter_code','patient_name', 'a.encounter_id', 'a.discharge_id', 'type_name','a.created_at', 'e.id','name','ward_name')
					->leftJoin('encounters as b', 'b.encounter_id','=','a.encounter_id')
					->leftJoin('patients as c', 'c.patient_id','=','b.patient_id')
					->leftJoin('ref_discharge_types as d', 'd.type_code','=','a.type_code')
					->leftJoin('bills as e', 'e.encounter_id', '=', 'a.encounter_id')
					->leftJoin('admissions as i', 'i.encounter_id', '=', 'b.encounter_id')
					->leftJoin('users as f', 'f.id', '=', 'a.user_id')
					->leftJoin('beds as g', 'g.bed_code', '=', 'i.bed_code')
					->leftJoin('wards as h', 'h.ward_code', '=', 'g.ward_code')
					->orderBy('e.id')
					->orderBy('discharge_id','desc');
			**/

			$discharges = DB::table('discharges as a')
					->select('patient_mrn', 'b.encounter_code','patient_name', 'a.encounter_id', 'a.discharge_id', 'type_name','a.created_at', 'name','ward_name')
					->leftJoin('encounters as b', 'b.encounter_id','=','a.encounter_id')
					->leftJoin('patients as c', 'c.patient_id','=','b.patient_id')
					->leftJoin('ref_discharge_types as d', 'd.type_code','=','a.type_code')
					->leftJoin('admissions as i', 'i.encounter_id', '=', 'b.encounter_id')
					->leftJoin('users as f', 'f.id', '=', 'a.user_id')
					->leftJoin('beds as g', 'g.bed_code', '=', 'i.bed_code')
					->leftJoin('wards as h', 'h.ward_code', '=', 'g.ward_code')
					->orderBy('discharge_id','desc');

			$discharges = $discharges->paginate($this->paginateValue);

			return view('discharges.index', [
					'discharges'=>$discharges,
					'discharge_types' => DischargeType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'type_code'=>null,
					'dojo' => new DojoUtility(),
					'dischargeHelper' => new DischargeHelper(),
					'encounters' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'encounter_code'=>null,
					'bill_helper'=>new BillHelper(),
			]);
	}

	public function create()
	{
			$id = Session::get('consultation_id');
			$consultation = Consultation::find($id);
			$discharge = new Discharge();
			$discharge->encounter_id = $consultation->encounter_id;
			$discharge->consultation_id = $id;
			$discharge->discharge_date = date("d/m/Y");
			$discharge->discharge_time = date("H:i");
			$discharge->user_id = Auth::user()->id;
			$discharge->type_code = 'home';

			//if ($consultation->encounter->encounter_code != 'inpatient') {
					$orders = Order::where('consultation_id',$consultation->consultation_id)
							->where('post_id',0)
							->update(['order_is_discharge'=>1]);
			//}

			$discharge_orders = DB::table('orders as a')
					->select(['a.product_code', 'product_name'])
					->leftJoin('products as b', 'b.product_code','=','a.product_code')
					->leftJoin('consultations as c', 'c.consultation_id','=','a.consultation_id')
					->leftJoin('encounters as d', 'd.encounter_id','=','c.encounter_id')
					->where('order_is_discharge',1)
					->where('d.encounter_id', $consultation->encounter_id)
					->where('b.category_code','<>','consultation')
					->get();

			$mc = $consultation->medical_certificate;

			$discharge_type = Type::where('is_mortuary',0)->orderBy('type_name')->lists('type_name', 'type_code')->prepend('','');
			if ($discharge->encounter->encounter_code=='mortuary') {
				$discharge_type = Type::where('is_mortuary',1)->orderBy('type_name')->lists('type_name', 'type_code')->prepend('','');
			}
			if ($discharge->encounter->encounter_code=='inpatient') {
					$discharge_type = Type::where('is_mortuary',0)
							->where('type_code','<>','admit')
							->orderBy('type_name')
							->lists('type_name', 'type_code')
							->prepend('','');
			}

			$fees = Order::where('category_code','consultation')
					->leftJoin('products as b', 'b.product_code', '=','orders.product_code')
					->leftJoin('order_cancellations as c', 'c.order_id', '=', 'orders.order_id')
					->where('encounter_id','=', $consultation->encounter_id)
					->where('orders.user_id','=', Auth::user()->id)
					->whereNull('cancel_id')
					->count();

			return view('discharges.create', [
					'discharge' => $discharge,
					'type' => $discharge_type,
					'consultation' => $consultation,
					'mc' => $consultation->medical_certificate,
					'patient' => $consultation->encounter->patient,
					'consultOption' => 'consultation',
					'discharge_orders' => $discharge_orders,
					'minYear' => Carbon::now()->year,
					'fees'=>$fees,
					]);
	}

	public function store(Request $request) 
	{
			$encounter = Encounter::find($request->encounter_id);
			if ($encounter->encounter_code == 'inpatient') {
					if (empty($request->discharge_summary)) $valid['discharge_summary']='This field is required.';
					if (empty($request->discharge_diagnosis)) $valid['discharge_diagnosis']='This field is required.';

					if (!empty($valid)) {
							return redirect('/discharges/create/')
									->withErrors($valid)
									->withInput();
					} 
			}

			$discharge = new Discharge();
			$valid = $discharge->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$discharge = new Discharge($request->all());
					$discharge->discharge_id = $request->discharge_id;
					$discharge->save();

					if ($encounter->admission) {
							$bed_charge = BedCharge::where('encounter_id', $discharge->encounter_id)
									->whereNull('bed_stop')
									->first();
							$bed_charge->bed_stop = date('d/m/Y');
							$bed_charge->block_room = $encounter->admission->block_room;
							$bed_charge->save();
					}

					DB::table('bill_items')
						->where('encounter_id','=',$discharge->encounter_id)
						->delete();

					$consultation = Consultation::findOrFail($discharge->consultation_id);
					$consultation->consultation_status = 1;
					$consultation->save();

					$post = new OrderPost();
					$post->consultation_id = $consultation->consultation_id;
					$post->save();

					OrderHelper::dropCharge($consultation->consultation_id);
					
					Order::where('consultation_id','=',$consultation->consultation_id)
							->where('post_id','=',0)
							->update(['post_id'=>$post->post_id, 'order_is_discharge'=>1]);

					Session::flash('message', 'Patient has been discharged.');
					return redirect('/patient_lists');
			} else {
					return redirect('/discharges/create/')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$discharge = Discharge::findOrFail($id);
			$consultation = $discharge->consultation;

			$mc = $consultation->medical_certificate;

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
					'patient' => $consultation->encounter->patient,
					'consultOption' => 'consultation',
					'mc' => $consultation->medical_certificate,
					'minYear' => Carbon::now()->year,
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

			$discharges = DB::table('discharges as a')
					->select('patient_mrn', 'patient_name', 'a.encounter_id', 'a.discharge_id', 'type_name','a.created_at', 'e.id','name','ward_name', 'b.encounter_code')
					->leftJoin('encounters as b', 'b.encounter_id','=','a.encounter_id')
					->leftJoin('patients as c', 'c.patient_id','=','b.patient_id')
					->leftJoin('ref_discharge_types as d', 'd.type_code','=','a.type_code')
					->leftJoin('bills as e', 'e.encounter_id', '=', 'a.encounter_id')
					->leftJoin('admissions as i', 'i.encounter_id', '=', 'b.encounter_id')
					->leftJoin('users as f', 'f.id', '=', 'a.user_id')
					->leftJoin('beds as g', 'g.bed_code', '=', 'i.bed_code')
					->leftJoin('wards as h', 'h.ward_code', '=', 'g.ward_code')
					->orderBy('discharge_id','desc');
			
			if (!empty($request->search)) {

					$discharges = $discharges->where(function ($query) use ($request) {
							$query->where('patient_mrn','like','%'.$request->search.'%')
								->orWhere('patient_name', 'like','%'.$request->search.'%');
					});
					//dd($discharges->toSql());
			}

			if (!empty($request->type_code)) {
					$discharges = $discharges->where('a.type_code','=', $request->type_code);
			}

			if (!empty($request->encounter_code)) {
					$discharges = $discharges->where('b.encounter_code','=', $request->encounter_code);
			}

			$discharges = $discharges->paginate($this->paginateValue);

			return view('discharges.index', [
					'discharges'=>$discharges,
					'search'=>$request->search,
					'discharge_types' => DischargeType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'type_code'=>$request->type_code,
					'dischargeHelper' => new DischargeHelper(),
					'encounters' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'encounter_code'=>$request->encounter_code,
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

	public function dischargeCount(Request $request) {
 
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$rows = Discharge::groupBy('b.sponsor_code')
					->leftJoin('encounters as b', 'b.encounter_id', '=', 'discharges.encounter_id')
					->leftJoin('sponsors as c', 'c.sponsor_code', '=', 'b.sponsor_code')
					->select(DB::raw('count(*) as visits, sponsor_name'));

			if (!empty($date_start) && empty($request->date_end)) {
				$rows = $rows->where('discharges.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$rows = $rows->where('discharges.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$rows = $rows->whereBetween('discharges.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			$rows = $rows->orderBy('visits');

			if ($request->export_report) {
				DojoUtility::export_report($rows->get());
			}

			$rows = $rows->get();
			$columns = ['sponsor_name'=>'Sponsor', 'visits'=>'Count'];

			$keys = array_keys($columns);

			return view('discharges.discharge_count', [
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'columns'=>$columns,
					'rows'=>$rows,
					'keys'=>$keys
			]);
	}

	public function enquiry(Request $request)
	{
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$subquery = "
				select a.encounter_id, a.created_at
				from consultations as a
				left join encounters as b on (a.encounter_id = b.encounter_id)
				where b.encounter_code = 'outpatient'
			";
			$discharges = Discharge::orderBy('discharge_id','desc')
					->select(DB::raw('b.created_at as encounter_date, concat(discharges.discharge_date," ", discharges.discharge_time) as discharge_date, patient_name, patient_mrn, encounter_name, ward_name,location_name, datediff(discharges.created_at, b.created_at) as LOS, timediff(outpatients.created_at, b.created_at) as waiting_time, type_name, name'))
					->leftJoin('encounters as b', 'b.encounter_id','=','discharges.encounter_id')
					->leftJoin('patients as c', 'c.patient_id','=','b.patient_id')
					->leftJoin('admissions as d', 'd.encounter_id', '=', 'b.encounter_id')
					->leftJoin('users as e', 'e.id', '=', 'discharges.user_id')
					->leftJoin('queues as f', 'f.encounter_id', '=', 'discharges.encounter_id')
					->leftJoin('ref_encounter_types as aa', 'aa.encounter_code','=','b.encounter_code')
					->leftJoin('beds as bb', 'bb.bed_code', '=', 'd.bed_code')
					->leftJoin('wards as cc', 'cc.ward_code', '=', 'bb.ward_code')
					->leftJoin('ref_discharge_types as dd', 'dd.type_code','=','discharges.type_code')
					->leftJoin('queue_locations as ee', 'ee.location_code', '=', 'f.location_code')
					->leftJoin(DB::raw('('.$subquery.') outpatients'), function($join) {
							$join->on('discharges.encounter_id','=', 'outpatients.encounter_id');
					})
					->orderBy('discharge_id','desc');
			
			if (!empty($request->search)) {
					$discharges = $discharges->where(function ($query) use ($request) {
							$query->where('patient_mrn','like','%'.$request->search.'%')
								->orWhere('patient_name', 'like','%'.$request->search.'%');
					});
			}

			if (!empty($request->outcome_code)) {
					$discharges = $discharges->where('discharges.type_code','=', $request->outcome_code);
			}

			if (!empty($request->encounter_code)) {
					$discharges = $discharges->where('b.encounter_code','=', $request->encounter_code);
			}

			if (!empty($request->flag_code)) {
					$discharges = $discharges->where('c.flag_code','=', $request->flag_code);
			}

			if (!empty($request->type_code)) {
					$discharges = $discharges->where('b.type_code','=', $request->type_code);
			}

			if (!empty($date_start) && empty($request->date_end)) {
				$discharges = $discharges->where('discharges.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$discharges = $discharges->where('discharges.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$discharges = $discharges->whereBetween('discharges.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if ($request->export_report) {
				return gettype($discharges->get());
				DojoUtility::export_report($discharges->get());
			}

			$discharges = $discharges->paginate($this->paginateValue);
			return view('discharges.enquiry', [
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'discharges'=>$discharges,
					'search'=>$request->search,
					'discharge_types' => DischargeType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'encounter_types' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'outcome_code'=>$request->outcome_code,
					'encounter_code'=>$request->encounter_code,
					'dojo' => new DojoUtility(),
					'flag' => PatientFlag::all()->sortBy('flag_name')->lists('flag_name', 'flag_code')->prepend('',''),
					'flag_code'=>$request->flag_code,
					'patient_types' => PatientType::all()->sortBy('type_name')->lists('type_name', 'type_code')->prepend('',''),
					'type_code' => $request->type_code,
					]);
	}
}
