<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PatientList;
use Log;
use DB;
use Session;
use App\QueueLocation as Location;
use Auth;
use App\Admission;
use App\Ward;
use App\DojoUtility;
use App\TeamMember;
use App\WardHelper;
use App\OrderHelper;
use App\EncounterHelper;
use App\Order;

class PatientListController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			if (empty($request->cookie('queue_location')) & empty(Auth::user()->location_code)) {
					Session::flash('message', 'Location not set. Please select your location or room.');
					return redirect('/queue_locations');
			}

			//if (!empty($request->cookie('queue_location'))) {
			if (empty(Auth::user()->location_code)) {
					$selectedLocation = $request->cookie('queue_location');
			} else {
					$selectedLocation = Auth::user()->location_code;
			}
			/*
			$outpatients = DB::table('queues as a')
					->select('f.user_id','discharge_id', 'location_name', 'queue_id','patient_mrn', 'patient_name', 'consultation_status', 'a.created_at', 'a.encounter_id', 'f.consultation_id')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftjoin('queue_locations as d', 'd.location_code','=', 'a.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->leftJoin('consultations as f', 'f.encounter_id','=', 'a.encounter_id')
					->where('a.location_code',$request->cookie('queue_location'))
					->whereNull('discharge_id')
					->orWhere('a.location_code','pool')
					->orderBy('discharge_id')
					->orderBy('a.created_at')
					->paginate($this->paginateValue);
			 */

			$location = Location::find($selectedLocation);

			if (!$location) {
					return redirect('/queue_locations');
			}

			$selectFields = ['patient_mrn', 'patient_name', 'a.created_at', 'a.encounter_id', 'b.patient_id', 'bed_name', 'ward_name', 'room_name','patient_birthdate', 'gender_name', 'sponsor_name'];

			//$team_member = TeamMember::where('username','=',Auth::user()->username)->first();

			$inpatients = DB::table('admissions as a')
							->select($selectFields)
							->leftJoin('encounters as b', 'b.encounter_id','=','a.encounter_id')
							->leftJoin('discharges as c', 'c.encounter_id', '=', 'a.encounter_id')
							->leftJoin('patients as d', 'd.patient_id', '=', 'b.patient_id')
							->leftJoin('beds as e', 'e.bed_code', '=', 'a.bed_code')
							->leftJoin('wards as i', 'i.ward_code', '=', 'e.ward_code')
							->leftJoin('ward_rooms as j', 'j.room_code', '=', 'e.room_code')
							->leftJoin('ref_genders as k', 'k.gender_code', '=', 'd.gender_code')
							->leftJoin('sponsors as l', 'l.sponsor_code', '=', 'b.sponsor_code')
							->where('a.user_id', Auth::user()->id)
							->where('b.encounter_code', 'inpatient')
							->whereNull('discharge_id')
							->get();

			$daycare = DB::table('admissions as a')
							->select($selectFields)
							->leftJoin('encounters as b', 'b.encounter_id','=','a.encounter_id')
							->leftJoin('discharges as c', 'c.encounter_id', '=', 'a.encounter_id')
							->leftJoin('patients as d', 'd.patient_id', '=', 'b.patient_id')
							->leftJoin('beds as e', 'e.bed_code', '=', 'a.bed_code')
							->leftJoin('wards as i', 'i.ward_code', '=', 'e.ward_code')
							->leftJoin('ward_rooms as j', 'j.room_code', '=', 'e.room_code')
							->leftJoin('ref_genders as k', 'k.gender_code', '=', 'd.gender_code')
							->leftJoin('sponsors as l', 'l.sponsor_code', '=', 'b.sponsor_code')
							->where('a.user_id', Auth::user()->id)
							->where('b.encounter_code', 'daycare')
							->whereNull('discharge_id')
							->get();
							//->where('a.user_id', Auth::user()->id)

			$observations = DB::table('admissions as a')
							->select($selectFields)
							->leftJoin('encounters as b', 'b.encounter_id','=','a.encounter_id')
							->leftJoin('discharges as c', 'c.encounter_id', '=', 'a.encounter_id')
							->leftJoin('patients as d', 'd.patient_id', '=', 'b.patient_id')
							->leftJoin('beds as e', 'e.bed_code', '=', 'a.bed_code')
							->leftJoin('wards as i', 'i.ward_code', '=', 'e.ward_code')
							->leftJoin('ward_rooms as j', 'j.room_code', '=', 'e.room_code')
							->leftJoin('ref_genders as k', 'k.gender_code', '=', 'd.gender_code')
							->leftJoin('sponsors as l', 'l.sponsor_code', '=', 'b.sponsor_code')
							->where('b.encounter_code', 'emergency')
							->whereNull('discharge_id')
							->get();

			$mortuary = DB::table('admissions as a')
							->select($selectFields)
							->leftJoin('encounters as b', 'b.encounter_id','=','a.encounter_id')
							->leftJoin('discharges as c', 'c.encounter_id', '=', 'a.encounter_id')
							->leftJoin('patients as d', 'd.patient_id', '=', 'b.patient_id')
							->leftJoin('beds as e', 'e.bed_code', '=', 'a.bed_code')
							->leftJoin('wards as i', 'i.ward_code', '=', 'e.ward_code')
							->leftJoin('ward_rooms as j', 'j.room_code', '=', 'e.room_code')
							->leftJoin('ref_genders as k', 'k.gender_code', '=', 'd.gender_code')
							->leftJoin('sponsors as l', 'l.sponsor_code', '=', 'b.sponsor_code')
							->where('b.encounter_code', 'mortuary')
							->whereNull('discharge_id')
							->get();

			/*
			$outpatients = DB::table('queues as a')
					->select('f.user_id','discharge_id', 'location_name', 'queue_id','patient_mrn', 'patient_name', 'consultation_status', 'a.created_at', 'a.encounter_id', 'f.consultation_id', 'patient_birthdate', 'gender_name','location_name')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftjoin('queue_locations as d', 'd.location_code','=', 'a.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->leftJoin('consultations as f', 'f.encounter_id','=', 'a.encounter_id')
					->leftJoin('ref_genders as k', 'k.gender_code', '=', 'c.gender_code')
					->where('a.location_code',$request->cookie('queue_location'))
					->whereNull('discharge_id')
					->whereNull('a.deleted_at')
					->orWhere('a.location_code','pool')
					->orderBy('discharge_id')
					->orderBy('a.created_at')
					->paginate($this->paginateValue);
			 */

			$outpatients = DB::table('queues as a')
					->select('f.user_id','discharge_id', 'location_name', 'queue_id','patient_mrn', 'patient_name', 'consultation_status', 'a.created_at', 'a.encounter_id', 'f.consultation_id', 'patient_birthdate', 'gender_name','location_name', 'encounter_description','sponsor_name','b.patient_id', 'queue_id', 'queue_description')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftjoin('queue_locations as d', 'd.location_code','=', 'a.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->leftJoin('consultations as f', function($q) 
					{
						$q->on('f.encounter_id','=','a.encounter_id')
		   				  ->where('f.user_id','=',Auth::user()->id)
		   				  ->where('consultation_status', '=', 1);
					})
					->leftJoin('ref_genders as k', 'k.gender_code', '=', 'c.gender_code')
					->leftJoin('sponsors as l', 'l.sponsor_code', '=', 'b.sponsor_code')
					->where('a.location_code',$location->location_code)
					->whereNull('discharge_id')
					->whereNull('a.deleted_at')
					->orWhere('a.location_code','pool')
					->orderBy('discharge_id')
					->orderBy('a.created_at')
					->paginate($this->paginateValue);

			$emergencies = DB::table('queues as a')
					->select('f.user_id','discharge_id', 'location_name', 'queue_id','patient_mrn', 'patient_name', 'consultation_status', 'a.created_at', 'a.encounter_id', 'f.consultation_id', 'patient_birthdate', 'gender_name','location_name', 'encounter_description','sponsor_name','b.patient_id', 'queue_id', 'queue_description')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->leftjoin('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftjoin('queue_locations as d', 'd.location_code','=', 'a.location_code')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'b.encounter_id')
					->leftJoin('consultations as f', function($q) 
					{
						$q->on('f.encounter_id','=','a.encounter_id')
		   				  ->where('f.user_id','=',Auth::user()->id)
		   				  ->where('consultation_status', '=', 1);
					})
					->leftJoin('ref_genders as k', 'k.gender_code', '=', 'c.gender_code')
					->leftJoin('sponsors as l', 'l.sponsor_code', '=', 'b.sponsor_code')
					->where('b.encounter_code', 'emergency')
					->whereNull('discharge_id')
					->whereNull('a.deleted_at')
					->orWhere('a.location_code','pool')
					->orderBy('discharge_id')
					->orderBy('a.created_at')
					->paginate($this->paginateValue);

			$orders = Order::where('orders.user_id', Auth::user()->id)
					->leftJoin('discharges as b', 'b.encounter_id', '=', 'orders.encounter_id')
					->leftJoin('consultations as c', 'c.consultation_id', '=', 'orders.consultation_id')
					->leftJoin('products as d', 'd.product_code', '=', 'orders.product_code')
					->leftjoin('order_cancellations as e', 'e.order_id', '=' , 'orders.order_id')
					->whereNull('discharge_id')
					->where('category_code', '<>', 'fee_consultation')
					->where('category_code', '<>', 'consumable')
					->where('category_code', '<>', 'wv')
					->where('product_drop_charge', 0)
					->where('post_id','>',0)
					->orderBy('e.cancel_id','desc')
					->orderBy('orders.created_at')
					->paginate($this->paginateValue);

			return view('patient_lists.index', [
					'orders'=>$orders,
					'outpatient_lists'=>$outpatients,
					'user_id' => Auth::user()->id,
					'location' => $location,
					'inpatients' => $inpatients,
					'observations' => $observations,
					'outpatients'=>$outpatients,
					'emergencies'=>$emergencies,
					'daycare' => $daycare,
					'mortuary' => $mortuary,
					'admission' => new Admission(),
					'dojo' => new DojoUtility(),
					'wardHelper'=> new WardHelper(null),
					'orderHelper'=>new OrderHelper(),
					'hasOpenOrders'=>OrderHelper::hasOpenOrders(Auth::user()->id),
					'encounterHelper'=> new EncounterHelper(),
			]);
	}

	public function create()
	{
			$patient_list = new PatientList();
			return view('patient_lists.create', [
					'patient_list' => $patient_list,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$patient_list = new PatientList();
			$valid = $patient_list->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$patient_list = new PatientList($request->all());
					$patient_list->queue_id = $request->queue_id;
					$patient_list->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/patient_lists/id/'.$patient_list->queue_id);
			} else {
					return redirect('/patient_lists/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$patient_list = PatientList::findOrFail($id);
			return view('patient_lists.edit', [
					'patient_list'=>$patient_list,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$patient_list = PatientList::findOrFail($id);
			$patient_list->fill($request->input());


			$valid = $patient_list->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$patient_list->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/patient_lists/id/'.$id);
			} else {
					return view('patient_lists.edit', [
							'patient_list'=>$patient_list,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$patient_list = PatientList::findOrFail($id);
		return view('patient_lists.destroy', [
			'patient_list'=>$patient_list
			]);

	}
	public function destroy($id)
	{	
			PatientList::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/patient_lists');
	}
	
	public function search(Request $request)
	{
			$patient_lists = DB::table('queues')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('queue_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('patient_lists.index', [
					'patient_lists'=>$patient_lists,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$patient_lists = DB::table('queues')
					->where('queue_id','=',$id)
					->paginate($this->paginateValue);

			return view('patient_lists.index', [
					'patient_lists'=>$patient_lists
			]);
	}
}
