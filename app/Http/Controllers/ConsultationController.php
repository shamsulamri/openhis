<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Consultation;
use Log;
use DB;
use Session;
use Auth;
use App\Patient;
use App\Order;
use App\OrderPost;
use App\Encounter;
use App\DojoUtility;
use App\DiagnosticOrder;
use App\AMQPHelper as Amqp;
use App\StockHelper;
use App\OrderHelper;
use App\OrderDrug;
use App\Set;
use App\EncounterHelper;

class ConsultationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$consultations = Consultation::orderBy('consultations.created_at','desc')
					->leftJoin('bills as c', 'c.encounter_id', '=', 'consultations.encounter_id')
					->whereNull('c.id')
					->paginate($this->paginateValue);

			return view('consultations.index', [
					'consultations'=>$consultations,
					'dojo'=>new DojoUtility(),
					'consult'=>new Consultation(),
			]);
	}

	public function progress($consultation_id) {
			$consultation = Consultation::find($consultation_id);
			$notes = Consultation::where('patient_id', $consultation->patient_id)
					->leftjoin('users as a', 'a.id', '=', 'consultations.user_id')
					->where('author_id', '=', Auth::user()->author_id)
					->orderBy('consultations.created_at','desc')
					->paginate(3);

			/*
					$notes = $note->where(function ($query) use ($request) {
								$query->where('product_name','like','%'.$search_param.'%')
								->orWhere('product_name_other','like','%'.$search_param.'%')
								->orWhere('products.product_code','like','%'.$search_param.'%');
					});
			 */

			return view('consultations.progress', [
					'notes'=>$notes,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'consultOption' => 'consultation',
					'order_helper'=>new OrderHelper(),
					'encounterHelper'=>new EncounterHelper(),
			]);
	}

	public function create(Request $request)
	{
			$encounter = Encounter::find($request->encounter_id);

			$consultation = DB::select("select consultation_id, encounter_id from consultations 
					where user_id = ".Auth::user()->id."
					and consultation_status=0
					and encounter_id = ".$request->encounter_id);

			//if ($encounter->encounter_code!='inpatient') {
					$consultation = DB::select("select consultation_id, encounter_id from consultations 
							where user_id = ".Auth::user()->id."
							and consultation_status=1
							and encounter_id = ".$request->encounter_id);

			//}

			if (!empty($consultation)) {
					$consultation_id = $consultation[0]->consultation_id;
			}

			$investigations = Set::where('set_shortcut', 1)
								->orderBy('set_name')
								->get();

			if (empty($consultation)==true) {
					Log::info("New consultation");
					$consultation = new Consultation();
					$consultation->user_id = Auth::user()->id;
					$consultation->encounter_id = $request->encounter_id;
					$consultation->patient_id = $encounter->patient_id;
					$consultation->consultation_status=1;
					$consultation->save();

					Session::set('consultation_id', $consultation->consultation_id);
					Session::set('encounter_id', $encounter->encounter_id);

					return view('consultations.edit', [
						'consultation'=>$consultation,
						'patient'=>$consultation->encounter->patient,
						'tab'=>'clinical',
						'consultOption'=>'consultation',
						'investigations'=>$investigations,
						'orders'=>$consultation->orders->pluck('product_code')->toArray(),
					]);
			} else {
					Log::info("Edit consultation");
					$consultation = Consultation::find($consultation[0]->consultation_id);
					Session::set('consultation_id', $consultation->consultation_id);
					Session::set('encounter_id', $encounter->encounter_id);
					return view('consultations.edit', [
						'consultation'=>$consultation,
						'tab'=>'clinical',
						'patient'=>$encounter->patient,
						'investigations'=>$investigations,
						'orders'=>$consultation->orders->pluck('product_code')->toArray(),
					]);
			};
	}

	public function store(Request $request) 
	{
			$consultation = new Consultation();
			$valid = $consultation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$consultation = new Consultation($request->all());
					$consultation->consultation_id = $request->consultation_id;
					$consultation->save();


					Session::flash('message', 'Record successfully created.');
					return redirect('/consultations/id/'.$consultation->consultation_id);
			} else {
					return redirect('/consultations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function close()
	{
			$id = Session::get('consultation_id');
			$consultation = Consultation::findOrFail($id);

			$this->orderStat($id);

			if ($consultation->encounter->encounter_code=='outpatient' or $consultation->encounter->encounter_code=='emergency') {
					if (Auth::user()->authorization->module_consultation == 1) {
						$consultation->consultation_status = 1;
					} else {
						$consultation->consultation_status = 2;
					}
			} else {
					$consultation->consultation_status = 2;
			}
			$consultation->save();

			$post = new OrderPost();
			$post->consultation_id = $id;
			$post->save();

			OrderHelper::dropCharge($id);

			Order::where('consultation_id','=',$id)
					->where('post_id','=',0)
					->update(['post_id'=>$post->post_id]);

			$this->postDiagnosticOrder($consultation);

			if (Auth::user()->authorization->module_consultation==1) {
					return redirect('/');
			} else {
					return redirect('/order_queues');
			}
	}

	public function orderStat($consultation_id) 
	{
			$order_helper = new OrderHelper();
			$stats = Order::where('order_include_stat',1)
						->where('consultation_id',	$consultation_id)
						->get();

			foreach ($stats as $stat) {

				$stat_id = $order_helper->orderItem($stat->product, $stat->admission->bed->ward->ward_code);

				$primary_order = OrderDrug::where('order_id', $stat->order_id)->first();

				$stat_order = OrderDrug::where('order_id', $stat_id)->first();
				$stat_order->frequency_code = 'STAT';
				$stat_order->drug_strength = $primary_order->drug_strength;
				$stat_order->unit_code = $primary_order->unit_code;
				$stat_order->drug_dosage = $primary_order->drug_dosage;
				$stat_order->dosage_code = $primary_order->dosage_code;
				$stat_order->route_code = $primary_order->route_code;
				$stat_order->save();


				$order = Order::find($stat_id);
				$order->order_quantity_request = $primary_order->drug_dosage;
				$order->save();
			}
	}

	public function postDiagnosticOrder($consultation)
	{
			$id = Session::get('consultation_id');
			$orders = Order::where('consultation_id','=',$id)
					->where('post_id','=',0)
					->get();

			if (count($orders)==0) return;

			$items = [];
			foreach ($orders as $order) {
				$status = 'requested';
				if (!empty($order->orderCancel->cancel_id)) {
					$status='cancelled';
				}

				$item = ['code'=>$order->product_code];
				//array_push($items, $item);

				$subject = ['reference'=>'Patient/'.$consultation->encounter->patient->patient_mrn];
				$orderer = ['reference'=>'Practitioner/'.$consultation->user->username,
							'display'=>$consultation->user->name
						];
				$encounter = ['id'=>$consultation->encounter->encounter_id,
						'class'=>$consultation->encounter->encounterType->encounter_name
				];
				if ($order->orderInvestigation) {
						$event = ['status'=>'requested', 'dateTime'=>$order->orderInvestigation->investigation_date ];
						Log::info("----- AMQP -----");
						Log::info($order);
						Log::info($order->orderInvestigation->investigation_date);

						$diagnostic = new DiagnosticOrder();
						$diagnostic->subject = $subject;
						$diagnostic->orderer = $orderer;
						$diagnostic->identifier = $order->order_id;
						$diagnostic->item = $item;
						$diagnostic->priority = $order->orderInvestigation->urgency_code;
						$diagnostic->encounter = $encounter;
						$diagnostic->note = $order->order_description;
						$diagnostic->status = $status;
						$diagnostic->event = $event;
						Amqp::pushMessage($order->product->location_code,json_encode($diagnostic,JSON_UNESCAPED_SLASHES));
				}
			}

	}

	public function edit($id) 
	{
			$consultation = Consultation::findOrFail($id);

			//return $consultation->annotation;
			Session::set('consultation_id', $consultation->consultation_id);
			Session::set('encounter_id', $consultation->encounter->encounter_id);

			$landing_page = "consultations.edit";
			/**
			if (Auth::user()->can('module-ward')) {
					$landing_page = "orders/make";
			}
			**/
			$clinical_images = [
					''=>'',
					'abdomen_f.png'=>'Abdomen (F)',
					'abdomen_m.png'=>'Abdomen (M)',
					'baby_back.png'=>'Baby Back',
					'baby_body_front_f.png'=>'Baby Back Front (F)',
					'baby_body_front_m.png'=>'Baby Back Front (M)',
					'back_dermatome.png'=>'Back Dermatome',
					'body_back_dermatome.png'=>'Body Back Dermatome',
					'body_back_f.png'=>'Body Back (F)',
					'body_back_m.png'=>'Body Back (M)',
					'body_front_dermatome.png'=>'Body Front Dermatome',
					'body_front_f.png'=>'Body Front (F)',
					'body_front_m.png'=>'Body Front (M)',
					'chest_back.png'=>'Chest Back',
					'chest_front_f.png'=>'Chest Front (F)',
					'chest_front_m.png'=>'Chest Front (M)',
					'chest_heart.png'=>'Chest Heart',
					'chest_l_side_f.png'=>'Chest L Side (F)',
					'chest_l_side_m.png'=>'Chest L Side (M)',
					'chest_r_side_f.png'=>'Chest R Side (F)',
					'chest_r_side_m.png'=>'Chest R Side (M)',
					'child_abdomen_f.png'=>'Child Abdomen (F)',
					'child_abdomen_m.png'=>'Child Abdomen (M)',
					'child_back.png'=>'Child Back',
					'child_body_front_f.png'=>'Child Body Front (F)',
					'child_body_front_m.png'=>'Chidl Body Front (M)',
					'child_chest_l_side.png'=>'Child Chest L Side',
					'child_chest_r_side.png'=>'Child Chest R Side',
					'eyes.png'=>'Eyes',
					'face_l_m.png'=>'Face Left (M)',
					'face_m.png'=>'Face (M)',
					'face_r_m.png'=>'Face Right (M)',
					'genitalia_f.png'=>'Genitalia (F)',
					'genitalia_m.png'=>'Genitalis (M)',
					'l_arm.png'=>'Left Arm',
					'l_ear.png'=>'Left Ear',
					'l_eye.png'=>'Left Eye',
					'l_fundus.png'=>'Left Fundus',
					'l_hand.png'=>'Left Hand',
					'l_leg.png'=>'Left Leg',
					'l_neck_f.png'=>'Left Neck (F)',
					'l_neck_m.png'=>'Left Neck (M)',
					'l_tympanic_membrane.png'=>'Left Tympanic Membrane',
					'neck_front.png'=>'Neck Front',
					'open_mouth_down.png'=>'Open Mouth Tongue Down',
					'open_mouth_up.png'=>'Open Mouth Tongue Up',
					'r_arm.png'=>'Right Arm',
					'r_ear.png'=>'Right Ear',
					'rectal_f.png'=>'Rectal (F)',
					'rectal_m.png'=>'Rectal (M)',
					'r_eye.png'=>'Right Eye',
					'r_fundus.png'=>'Right Fundus',
					'r_hand.png'=>'Right Hand',
					'r_leg.png'=>'Right Leg',
					'r_neck_f.png'=>'Right Neck (F)',
					'r_neck_m.png'=>'Right Neck (M)',
					'r_tympanic_membrane.png'=>'Right Tympanic Membrane',
			];

			if ($consultation->encounter->patient->gender_code=='L') {
					$clinical_images = [
							''=>'',
							'abdomen_m.png'=>'Abdomen (M)',
							'baby_back.png'=>'Baby Back',
							'baby_body_front_m.png'=>'Baby Back Front (M)',
							'back_dermatome.png'=>'Back Dermatome',
							'body_back_dermatome.png'=>'Body Back Dermatome',
							'body_back_m.png'=>'Body Back (M)',
							'body_front_dermatome.png'=>'Body Front Dermatome',
							'body_front_m.png'=>'Body Front (M)',
							'chest_back.png'=>'Chest Back',
							'chest_front_m.png'=>'Chest Front (M)',
							'chest_heart.png'=>'Chest Heart',
							'chest_l_side_m.png'=>'Chest L Side (M)',
							'chest_r_side_m.png'=>'Chest R Side (M)',
							'child_abdomen_m.png'=>'Child Abdomen (M)',
							'child_back.png'=>'Child Back',
							'child_body_front_m.png'=>'Chidl Body Front (M)',
							'child_chest_l_side.png'=>'Child Chest L Side',
							'child_chest_r_side.png'=>'Child Chest R Side',
							'eyes.png'=>'Eyes',
							'face_l_m.png'=>'Face Left',
							'face_m.png'=>'Face',
							'face_r_m.png'=>'Face Right',
							'genitalia_m.png'=>'Genitalis (M)',
							'l_arm.png'=>'Left Arm',
							'l_ear.png'=>'Left Ear',
							'l_eye.png'=>'Left Eye',
							'l_fundus.png'=>'Left Fundus',
							'l_hand.png'=>'Left Hand',
							'l_leg.png'=>'Left Leg',
							'l_neck_m.png'=>'Left Neck (M)',
							'l_tympanic_membrane.png'=>'Left Tympanic Membrane',
							'neck_front.png'=>'Neck Front',
							'open_mouth_down.png'=>'Open Mouth Tongue Down',
							'open_mouth_up.png'=>'Open Mouth Tongue Up',
							'r_arm.png'=>'Right Arm',
							'r_ear.png'=>'Right Ear',
							'rectal_m.png'=>'Rectal (M)',
							'r_eye.png'=>'Right Eye',
							'r_fundus.png'=>'Right Fundus',
							'r_hand.png'=>'Right Hand',
							'r_leg.png'=>'Right Leg',
							'r_neck_m.png'=>'Right Neck (M)',
							'r_tympanic_membrane.png'=>'Right Tympanic Membrane',
					];
			}

			if ($consultation->encounter->patient->gender_code=='P') {
					$clinical_images = [
							''=>'',
							'abdomen_f.png'=>'Abdomen (F)',
							'baby_back.png'=>'Baby Back',
							'baby_body_front_f.png'=>'Baby Back Front (F)',
							'back_dermatome.png'=>'Back Dermatome',
							'body_back_dermatome.png'=>'Body Back Dermatome',
							'body_back_f.png'=>'Body Back (F)',
							'body_front_dermatome.png'=>'Body Front Dermatome',
							'body_front_f.png'=>'Body Front (F)',
							'chest_back.png'=>'Chest Back',
							'chest_front_f.png'=>'Chest Front (F)',
							'chest_heart.png'=>'Chest Heart',
							'chest_l_side_f.png'=>'Chest L Side (F)',
							'chest_r_side_f.png'=>'Chest R Side (F)',
							'child_abdomen_f.png'=>'Child Abdomen (F)',
							'child_back.png'=>'Child Back',
							'child_body_front_f.png'=>'Child Body Front (F)',
							'child_chest_l_side.png'=>'Child Chest L Side',
							'child_chest_r_side.png'=>'Child Chest R Side',
							'eyes.png'=>'Eyes',
							'face_l_m.png'=>'Face Left',
							'face_m.png'=>'Face',
							'face_r_m.png'=>'Face Right',
							'genitalia_f.png'=>'Genitalia (F)',
							'l_arm.png'=>'Left Arm',
							'l_ear.png'=>'Left Ear',
							'l_eye.png'=>'Left Eye',
							'l_fundus.png'=>'Left Fundus',
							'l_hand.png'=>'Left Hand',
							'l_leg.png'=>'Left Leg',
							'l_neck_f.png'=>'Left Neck (F)',
							'l_tympanic_membrane.png'=>'Left Tympanic Membrane',
							'neck_front.png'=>'Neck Front',
							'open_mouth_down.png'=>'Open Mouth Tongue Down',
							'open_mouth_up.png'=>'Open Mouth Tongue Up',
							'r_arm.png'=>'Right Arm',
							'r_ear.png'=>'Right Ear',
							'rectal_f.png'=>'Rectal (F)',
							'r_eye.png'=>'Right Eye',
							'r_fundus.png'=>'Right Fundus',
							'r_hand.png'=>'Right Hand',
							'r_leg.png'=>'Right Leg',
							'r_neck_f.png'=>'Right Neck (F)',
							'r_tympanic_membrane.png'=>'Right Tympanic Membrane',
					];
			}

			$investigations = Set::where('set_shortcut', 1)
								->orderBy('set_name')
								->get();

			return view($landing_page, [
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'clinical',
					'consultOption'=>'consultation',
					'admission'=>$consultation->encounter->admission,
					'clinical_images'=>$clinical_images,
					'orders'=>$consultation->orders->pluck('product_code')->toArray(),
					'investigations'=>$investigations,
					'encounter'=>$consultation->encounter,
					]);
	}

	public function update(Request $request, $id) 
	{
			if ($request->ajax()) {
					$consultation = Consultation::find($request->id);
					$consultation->consultation_notes = $request->consultation_note;
					$consultation->save();
					return "Ok";
			}
	}

	public function update_backup(Request $request, $id) 
	{
			$consultation = Consultation::findOrFail($id);
			$consultation->fill($request->input());


			$valid = $consultation->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$consultation->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultations/'.$id.'/edit');
					//return redirect('/consultation_diagnoses');
			} else {
					return view('consultations.edit', [
							'consultation'=>$consultation,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$consultation = Consultation::findOrFail($id);
		return view('consultations.destroy', [
			'consultation'=>$consultation
			]);

	}
	public function destroy($id)
	{	
			Consultation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultations');
	}
	
	public function search(Request $request)
	{
			$consultations = DB::table('consultations as a')
					->leftjoin('patients as b','b.patient_id','=', 'a.patient_id')
					->where('patient_name','like','%'.$request->search.'%')
					->orWhere('patient_mrn', 'like','%'.$request->search.'%')
					->orderBy('consultation_status')
					->paginate($this->paginateValue);

			return view('consultations.index', [
					'consultations'=>$consultations,
					'search'=>$request->search,
					'consult'=>new Consultation(),
					'dojo'=>new DojoUtility(),
					]);
	}

	public function searchById($id)
	{
			$consultations = DB::table('consultations')
					->where('consultation_id','=',$id)
					->paginate($this->paginateValue);

			return view('consultations.index', [
					'consultations'=>$consultations
			]);
	}

	public function medicationAdministrationRecord() {
			$id = Session::get('consultation_id');
			$consultation = Consultation::find($id);

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
					'frequency_value',
					];

			$drugs = DB::table('orders as a')
					->select($fields)
					->join('products as b','a.product_code','=','b.product_code')
					->leftjoin('order_cancellations as c', 'c.order_id', '=', 'a.order_id')
					->leftjoin('consultations as d', 'd.consultation_id', '=', 'a.consultation_id')
					->leftjoin('product_categories as e', 'e.category_code', '=', 'b.category_code')
					->leftjoin('order_drugs as f', 'f.order_id', '=', 'a.order_id')
					->leftjoin('drug_frequencies as g', 'g.frequency_code', '=', 'f.frequency_code')
					->where('a.encounter_id','=',$consultation->encounter_id)
					->where('b.category_code','=','drugs')
					->orderBy('b.category_code')
					->orderBy('a.created_at', 'desc')
					->get();

			return view('consultations.mar', [
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'consultOption'=>'consultation',
					'admission'=>$consultation->encounter->admission,
					'drugs'=>$drugs,
					]);
	}

	public function getConsultation(Request $request)
	{
			Log::info($request->note);
			if ($request->ajax()) {
						Log::info("qqqqq");
						return "Ajax get";
			}
	}

	public function setConsultation(Request $request)
	{
			if ($request->ajax()) {
					$consultation = Consultation::find($request->id);
					$consultation->consultation_notes = $request->note;
					$consultation->save();
			}
	}
}
