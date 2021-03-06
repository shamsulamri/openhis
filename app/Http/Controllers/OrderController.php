<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order;
use Log;
use DB;
use Session;
use App\Product;
use App\QueueLocation as Location;
use App\Consultation;
use App\Encounter;
use App\EncounterType;
use Auth;
use App\OrderInvestigation;
use App\OrderDrug;
use App\DrugPrescription;
use App\DojoUtility;
use App\EncounterHelper;
use App\OrderHelper;
use App\Ward;
use App\StockHelper;
use App\User;
use App\ProductCategory;
use App\AMQPHelper as Amqp;
use App\Document;
use App\Set;
use App\Store;

class OrderController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function getCurrentConsultationId($encounter_id)
	{
			$consultation = Consultation::select('consultation_id')
					->where('encounter_id',$encounter_id)
					->where('user_id', Auth::user()->id)
					->orderBy('created_at','desc')
					->limit(1)
					->get()[0];

			return $consultation->consultation_id;		
	}

	public function show($id)
	{
			$order = Order::findOrFail($id);
			$product = Product::find($order->product_code);
			$consultation = Consultation::findOrFail($order->consultation_id);
			$current_id = Consultation::orderBy('created_at','desc')->limit(1)->get()[0]->consultation_id;

			$diagnostic = json_decode($order->order_diagnostic_report,true);
			//$code = json_encode($diagnostic['code']);

			if ($diagnostic['contained']) {
					foreach ($diagnostic['contained'] as $row) {
							$diagnostic_name = $row['code']['text'];
							$diagnostic_value = $row['valueQuantity']['value'] . $row['valueQuantity']['unit'];
							$diagnostic_low = null;
							$diagnostic_high = null;
							if (!empty($row['referenceRange'][0])) {
								if (!empty($row['referenceRange'][0]['high'])) {
									$diagnostic_high = $row['referenceRange'][0]['high']['value'];
								}
								if (!empty($row['referenceRange'][0]['low'])) {
									$diagnostic_low = $row['referenceRange'][0]['low']['value'];
								}
							}
							$diagnostic_interpretation = null;
							if (!empty($row['interpretation'])) {
									$diagnostic_interpretation = $row['interpretation']['coding'][0]['code'];
							}

							Log::info("$diagnostic_name $diagnostic_value $diagnostic_low-$diagnostic_high $diagnostic_interpretation");
					}
					Log::info($diagnostic['text']['status']);
			}
			//return ($diagnostic['contained']);

			$document = Document::where('order_id', $id)->first();

			return view('orders.show', [
				'order'=>$order,
				'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
				'consultation' => $consultation,
				'patient'=>$consultation->encounter->patient,
				'tab'=>'order',
				'product'=>$product,
				'current_id'=>$current_id,
				'consultOption' => 'consultation',
				'diagnostic' => $diagnostic,
				'document'=>$document,
			]);
	}

	public function index(Request $request)
	{
			$encounter= Encounter::find(Session::get('encounter_id'));
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			$fields = ['product_name', 
					'orders.product_code', 
					'cancel_id', 
					'orders.order_id', 
					'orders.user_id', 
					'order_quantity_request',
					'order_quantity_supply',
					'post_id', 
					'd.created_at',
					'order_is_discharge',
					'order_completed',
					'order_report',
					'category_name',
					'product_edit_price',
					'document_uuid',
					'order_unit_price',
					'bom_code',
					'd.created_at as consultation_date'
					];

			//$orders = DB::table('orders as a')
			$orders = Order::select($fields)
					->join('products as b','orders.product_code','=','b.product_code')
					->leftjoin('order_cancellations as c', 'c.order_id', '=', 'orders.order_id')
					->leftjoin('consultations as d', 'd.consultation_id', '=', 'orders.consultation_id')
					->leftjoin('product_categories as e', 'e.category_code', '=', 'b.category_code')
					->leftjoin('documents as f', 'f.order_id', '=', 'orders.order_id')
					->where('orders.encounter_id','=',$encounter->encounter_id)
					->orderBy('orders.created_at', 'desc');

					//->orderBy('b.category_code')
			if (Auth::user()->authorization->module_support != 1) {
					if (Auth::user()->authorization->module_discharge != 1) {
							//$orders = $orders->where('orders.user_id','=',Auth::user()->id);
					}
			}

			if ($encounter->admission) {
				if (!empty($consultation->transit_ward)) {
					$orders = $orders->where('ward_code', $consultation->transit_ward);
				} else {
					$orders = $orders->where('ward_code', $encounter->admission->bed->ward_code);
				}
			}

			/*
			if (!empty(Auth::user()->authorization->location_code)) {
				$location_code = Auth::user()->authorization->location_code;
				$orders = $orders->where('a.location_code','=', $location_code);
			} 
			 */

			if (Auth::user()->authorization->module_support == 1) {
					//$orders = $orders->where('a.location_code','=', $request->cookie('queue_location'));
			}

			if (!empty($request->search)) {
				$orders = $orders->where(function ($query) use ($request) {
						$search_param = trim($request->search, " ");
						$query->where('b.product_name','like','%'.$search_param.'%')
								->orWhere('b.product_name_other','like','%'.$search_param.'%')
								->orWhere('b.product_code','like','%'.$search_param.'%');
					});
			}

			$orders = $orders->paginate($this->paginateValue);

			return view('orders.index', [
					'orders'=>$orders,
					'consultation'=>$consultation,
					'patient'=>$encounter->patient,
					'tab'=>'order',
					'consultOption' => 'consultation',
					'dojo'=> new DojoUtility(),
					'search'=>$request->search,
			]);
	}

	public function create($product_code)
	{
			$consultation_id = Session::get('consultation_id');
			$product=Product::find($product_code);
			$order = new Order();
			$order->consultation_id = $consultation_id;
			$order->product_code = $product_code;
			$order->order_quantity_request=1;

			$consultation = Consultation::findOrFail($consultation_id);

		 	if ($product->order_form == '2') {
				return redirect('/order_drugs/create/'.$product_code);
			} elseif ($product->order_form == '3') {
				return redirect('/order_investigations/create/'.$product_code);
			} else {
				return view('orders.create', [
					'order' => $order,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'tab'=>'order',
					'consultOption' => 'consultation',
					'product'=>$product,
					]);
			}
	}

	public function store(Request $request) 
	{
			$order = new Order();
			$valid = $order->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product = Product::find($request->product_code);
					$order = new Order($request->all());
					$order->encounter_id = Session::get('encounter_id');
					$order->order_id = $request->order_id;
					$order->user_id = Auth::user()->id;
					$order->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/orders');
			} else {
					return redirect('/orders/create/'.$request->consultation_id.'/'.$request->product_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function updateDiagnosticReport(Request $request, $id) 
	{
			$order = Order::find($id);
			$order->order_diagnostic_report = $request->report;
			$order->save();
	}

	public function edit(Request $request, $id) 
	{
			$encounter= Encounter::find(Session::get('encounter_id'));
			$consultation = new Consultation();
			if (!empty(Session::get('consultation_id'))) {
					$consultation = Consultation::findOrFail(Session::get('consultation_id'));
			}
			$order = Order::findOrFail($id);

			$product = Product::find($order->product_code);
			$current_id = Consultation::orderBy('created_at','desc')->limit(1)->get()[0]->consultation_id;

			if (Auth::user()->author_id==2) {
					if ($product->order_form == '2') {
							return redirect('/order_drugs/'.$order->orderDrug->id.'/edit?order_single='.$request->order_single);
					} elseif ($product->order_form == '3') {
							return redirect('/order_investigations/'.$order->orderInvestigation->id.'/edit?order_single='.$request->order_single);
					} 
			}

			$stock_helper = new StockHelper();
			$available = $stock_helper->getStockAvailable($order->product_code, $order->store_code);

			return view('orders.edit', [
					'order'=>$order,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'consultation' => $consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'order',
					'product'=>$product,
					'current_id'=>$current_id,
					'available'=>$available,
					'order_single'=>$request->order_single,
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
			]);
	}

	public function validateOrder($request, $order) 
	{
			$stock_helper = new StockHelper();
			$product = Product::find($order->product_code);
			$valid = null;
			if ($product->product_stocked==1) {
					$on_hand = $stock_helper->getStockOnHand($order->product_code, $order->store_code);
					$allocated = $stock_helper->getStockAllocated($order->product_code, $order->store_code, Session::get('encounter_id'));

					if ($order->order_quantity_request>$on_hand-$allocated) {
							$valid = $order->validate($request->all(), $request->_method);	
							$valid->getMessageBag()->add('order_quantity_request', 'Insufficient quantity.');
					}
			}
			return $valid;
	}

	public function update(Request $request, $id) 
	{
			$order = Order::findOrFail($id);
			$order->fill($request->input());

			$order->order_completed = $request->order_completed ?: 0;
			$order->order_is_discharge = $request->order_is_discharge ?: 0;

			$product = Product::find($order->product_code);

			/*
			$valid = $this->validateOrder($request, $order);

			if ($valid) {
					return redirect('/orders/'.$order->order_id.'/edit')
							->withErrors($valid)
							->withInput();
			}
			 */

			$valid = $order->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order->order_quantity_supply = $order->order_quantity_request;
					if ($request->order_completed == 1) {
						$location_code = $request->cookie('queue_location');
						$admission = EncounterHelper::getCurrentAdmission($order->encounter_id);
						$order->location_code = $location_code;

						if ($order->product->product_stocked==1) {
								$store_code = OrderHelper::getLocalStore($order->consultation->encounter, $admission);
								$order->store_code = $store_code;
						}
					}
					$order->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/orders/');
			} else {
					return view('orders.edit', [
							'order'=>$order,
							'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$consultation = Consultation::find(Session::get('consultation_id'));
		$order = Order::findOrFail($id);
		return view('orders.destroy', [
					'order'=>$order,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'order',
					'consultOption' => 'consultation',
			]);

	}

	public function destroy($id)
	{	
			$order = Order::find($id);
			Order::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/orders/');
	}
	
	public function cancelSingle($id)
	{
			$this->destroy($id);
			Session::flash('message', 'Record deleted.');
			return redirect('/orders/');
	}

	public function search(Request $request)
	{
			$orders = DB::table('orders')
					->where('product_code','like','%'.$request->search.'%')
					->orWhere('order_id', 'like','%'.$request->search.'%')
					->orderBy('product_code')
					->paginate($this->paginateValue);

			return view('orders.index', [
					'orders'=>$orders,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$orders = DB::table('orders')
					->where('order_id','=',$id)
					->paginate($this->paginateValue);

			return view('orders.index', [
					'orders'=>$orders
			]);
	}

	public function save(array $options = array())
	{
			$changed = $this->isDirty() ? $this->getDirty() : false;


			parent::save();

			if ($changed) 
			{
				//Log::info("save !!!!!");
			}	
	}

	public function multiple(Request $request) 
	{
			foreach ($request->all() as $product_code=>$value) {
					switch ($product_code) {
							case '_token':
									break;
							case '_set_value':
									break;
							case '_search':
									break;
							case '_page':
									break;
							default:
									$product = Product::find($product_code);
									OrderHelper::orderItem($product, $request->cookie('ward'));
									//$order = $this->orderItem($product);
					}
			}

			//Session::flash('message', 'Product added to order list.');
			return redirect('/order_product/search?search='.$request->_search.'&set_code='.$request->_set_value.'&page='.$request->_page);
			//return redirect('/orders');
	}

	public function removeOrder(Request $request) 
	{
			Log::info("Remove order....");
			$product_code = $request->product_code;
			$encounter_id = Session::get('encounter_id');
			$consultation_id = Session::get('consultation_id');
			$orders = Order::select('orders.order_id')
						->where('product_code','=', $product_code)
						->leftJoin('order_cancellations as b', 'b.order_id', '=', 'orders.order_id')
						->where('encounter_id','=', $encounter_id)
						->where('consultation_id','=', $consultation_id)
						->where('order_completed','=','0')
						->whereNull('cancel_id')
						->get();

			$encounter = Encounter::find($encounter_id);
			if (!$encounter->admission) {
					if ($orders->count()>0) {
							foreach($orders as $order) {
									$id = $order->order_id;
									if (!empty($id)) {
											$order = Order::find($id);
											Order::find($id)->delete();
									}
							}
					}
			}
	}

	public function addOrder(Request $request)
	{
			$product_code = $request->product_code;
			Log::info("Add order.......".$product_code);
			Log::info($request);
			$encounter_id = Session::get('encounter_id');
			$orders = Order::where('product_code','=', $product_code)
						->leftJoin('order_cancellations as b', 'b.order_id', '=', 'orders.order_id')
						->where('encounter_id','=', $encounter_id)
						->where('order_completed','=','0')
						->whereNull('cancel_id')
						->get();

			$encounter = Encounter::find($encounter_id);
			if (!$encounter->admission) {
					if ($orders->count()>0) {
							foreach($orders as $order) {
									if ($order->order_completed == 0) {
											$id = $order->order_id;
											if (!empty($id)) {
													$order = Order::find($id);
													Order::find($id)->delete();
											}
									}
							}
							Log::info("Item alread exist.");
							return;
					}
			}

			$product = Product::find($product_code);
			$order_id = OrderHelper::orderItem($product, $request->cookie('ward'));
			Log::info($order_id);

			$order = Order::find($order_id);
			$order->order_unit_price = $request->amount;
			$order->save();
			return $order_id;
	}

	public function updateOrder(Request $request)
	{
			Log::info("Update order");
			$product_code = $request->product_code;
			$encounter_id = Session::get('encounter_id');
			$consultation_id = Session::get('consultation_id');
			$order = Order::where('product_code','=', $product_code)
						->where('encounter_id','=', $encounter_id)
						->where('consultation_id','=', $consultation_id)
						->where('order_completed','=','0')
						->first();

			if (!empty($order)) {
					$order->order_unit_price = $request->amount;
					$order->save();
					Log::info($encounter_id);
					Log::info($consultation_id);
					Log::info($order);
					Log::info($order->order_unit_price);
			}
	}

	public function single(Request $request, $product_code)
	{

			$encounter_id = Session::get('encounter_id');
			$orders = Order::where('product_code','=', $product_code)
						->leftJoin('order_cancellations as b', 'b.order_id', '=', 'orders.order_id')
						->where('encounter_id','=', $encounter_id)
						->where('order_completed','=','0')
						->whereNull('cancel_id')
						->get();

			$encounter = Encounter::find($encounter_id);

			$product = Product::find($product_code);
			$order_id = OrderHelper::orderItem($product, $request->cookie('ward'));
			if ($order_id>0) {
				return redirect('/order_product/search?search='.$request->_search.'&set_code='.$request->_set_value.'&page='.$request->_page.'&order_id='.$order_id.'&categories='.$request->category_code);
			} else {
				return redirect('/order_product/search?search='.$request->_search.'&set_code='.$request->_set_value.'&page='.$request->_page);

			}
	}

	public function plan(Request $request)
	{
			$encounter= Encounter::find(Session::get('encounter_id'));
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			$plans = [];
			if ($request->plan=='laboratory') { $plans = ['lab']; }
			if ($request->plan=='imaging') { $plans = ['radiography']; }
			if ($request->plan=='procedure') { $plans = ['fee_procedure']; }
			if ($request->plan=='fee_consultant') { $plans = ['fee_consultant']; }

			$view = 'orders.plan';
			$ordered_items = array();
			$amounts = [];
			if ($request->plan=='fee_consultant') {
					$view = 'orders.consultant_fee';
					$favorites = Product::select('product_code')
							->where('product_code', 'like', '%A')
							->where('category_code', $plans)
							->orderBy('product_name')
							->pluck('product_code');

					$ordered_items = Order::select('product_code')
										->where('consultation_id', $consultation->consultation_id)
										->pluck('product_code')
										->toArray();
					$ordered_amounts = Order::select('product_code', 'order_unit_price')
										->where('consultation_id', $consultation->consultation_id)
										->get();

					foreach($ordered_amounts as $ordered_amount) {
							$product_code = $ordered_amount->product_code;
							$amounts[substr($product_code, 0, strlen($product_code)-1)] = $ordered_amount->order_unit_price;
					}


			} else {
				$favorites = DB::table('orders')
							->select('orders.product_code', DB::raw('count(*) as total'))
							->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
							->whereIn('category_code', $plans)
							->groupBy('orders.product_code')
							->orderBy('total', 'desc');

				if (Auth::user()->consultant==1) {
						$favorites = $favorites->where('user_id', Auth::user()->id);
				}

				$favorites = $favorites->limit(30)
								   ->pluck('orders.product_code');

				if (sizeof($favorites)==0) {
						$favorites = ['61050371', '61060010'];
				}
			}

			$products = Product::whereIn('product_code', $favorites)
							->orderBy('category_code')
							->orderBy('product_name')
							->get();

			$half = 1;
			$chunks = [];
			if ($products->count()>=14) {
				$half = ceil($products->count()/2);
				$chunks = $products->chunk($half);
			} else {
				$chunks[0] = $products;
			}

			return view($view, [
					'consultation'=>$consultation,
					'patient'=>$encounter->patient,
					'encounter'=>$encounter,
					'tab'=>'order',
					'products'=> $chunks,
					'consultOption' => 'consultation',
					'orders'=>$consultation->orders->pluck('product_code')->toArray(),
					'plan'=>$request->plan,
					'half'=>$half,
					'order_helper'=>new OrderHelper(),
					'ordered_items'=>$ordered_items,
					'amounts'=>$amounts,
			]);
	}

	public function procedure()
	{
			$encounter= Encounter::find(Session::get('encounter_id'));
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			return view('orders.procedure', [
					'consultation'=>$consultation,
					'patient'=>$encounter->patient,
					'encounter'=>$encounter,
					'plan'=>'procedure',
			]);
	}

	public function medication()
	{
			$consultation_id = Session::get('consultation_id');
			$consultation = Consultation::findOrFail($consultation_id);
			$encounter_id = $consultation->encounter_id;

			$medications = OrderDrug::orderBy('b.created_at')
						->leftJoin('orders as b', 'b.order_id', '=', 'order_drugs.order_id')
						->where('encounter_id', $encounter_id)
						->get();

			return view('orders.medication', [
					'medications'=>$medications,
					'patient'=>$consultation->encounter->patient,
					'consultation'=>$consultation,
					'plan'=>'medication',
			]);
	}

	public function discussion()
	{
			$encounter= Encounter::find(Session::get('encounter_id'));
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			return view('orders.discussion', [
					'consultation'=>$consultation,
					'patient'=>$encounter->patient,
					'encounter'=>$encounter,
					'plan'=>'discussion',
			]);
	}

	public function postPlan(Request $request)
	{
			Log::info("Post plan....");
			Log::info($request->consultation_plan);
			
			$consultation = Consultation::find($request->consultation_id);
			$consultation->consultation_plan = $request->consultation_plan;
			$consultation->save();
			/*
			$history_note = ConsultationHistory::where('patient_id', $request->patient_id)	
					->where('history_code', $request->history_code)
					->first();

			if (empty($history_note)) {
					$history_note = new ConsultationHistory();
					$history_note->patient_id = $request->patient_id;
					$history_note->history_code = $request->history_code;
			}

			$history_note->history_note = $request->history_note;
			$history_note->save();

			Log::info($history_note);
			return $request->history_code." saved...";
			 */
			return "Saved...";

	}

	public function make()
	{
			$encounter= Encounter::find(Session::get('encounter_id'));
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			/*
			$fields = ['product_name', 
					'a.product_code', 
					'cancel_id', 
					'a.order_id', 
					'post_id', 
					'a.created_at',
					'order_is_discharge',
					'order_completed',
					'order_report',
					];
			$orders = DB::table('orders as a')
					->select($fields)
					->join('products as b','a.product_code','=','b.product_code')
					->leftjoin('order_cancellations as c', 'c.order_id', '=', 'a.order_id')
					->leftjoin('consultations as d', 'd.consultation_id', '=', 'a.consultation_id')
					->where('a.encounter_id','=',Session::get('encounter_id'))
					->orderBy('a.created_at', 'desc')
					->paginate($this->paginateValue);

					'orders'=>$orders,
			*/
			return view('orders.make', [
					'consultation'=>$consultation,
					'patient'=>$encounter->patient,
					'encounter'=>$encounter,
					'tab'=>'order',
					'consultOption' => 'consultation',
					'plan'=>'order',
			]);
	}

	public function enquiry(Request $request)
	{
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			/**
			$orders = Order::select(
					'b.encounter_id', 
					'encounter_name',
					'patient_name', 
					'patient_mrn', 
					'type_name',
					'product_name', 
					'e.product_code', 
					'inv_unit_cost', 
					'order_unit_price', 
					'f.name', 
					'k.created_at as consultation_date', 
					'j.name as completed_name', 
					'completed_at', 
					'l.name as dispensed_name', 
					'dispensed_at',
					'order_completed', 
					'orders.order_id', 
					'cancel_id', 
					'cancel_reason', 
					'post_id', 
					DB::raw('TIMEDIFF(completed_at, orders.created_at) as turnaround'),
					DB::raw('TIMEDIFF(IFNULL(completed_at, now()),orders.created_at) as age'), 
					'order_quantity_supply'
					)
					->leftJoin('encounters as b', 'b.encounter_id', '=', 'orders.encounter_id')
					->leftJoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->leftJoin('products as e', 'e.product_code', '=', 'orders.product_code')
					->leftJoin('users as f', 'f.id', '=', 'orders.user_id')
					->leftJoin('order_cancellations as g', 'g.order_id', '=', 'orders.order_id')
					->leftJoin('ref_patient_types as h', 'h.type_code', '=', 'b.type_code')
					->leftJoin('inventories as i', 'i.order_id', '=', 'orders.order_id')
					->leftJoin('users as j', 'j.id', '=', 'orders.completed_by')
					->leftJoin('consultations as k','k.consultation_id', '=', 'orders.consultation_id')
					->leftJoin('users as l', 'l.id', '=', 'orders.dispensed_by')
					->leftJoin('ref_encounter_types as m', 'm.encounter_code', '=', 'b.encounter_code')
					->orderBy('b.encounter_id');
			**/

			$orders = Order::select(
					'b.encounter_id', 
					'encounter_name',
					'patient_name', 
					'patient_mrn', 
					'type_name',
					'product_name', 
					'e.product_code', 
					'inv_unit_cost', 
					'order_unit_price', 
					'f.name', 
					DB::raw('DATE_FORMAT(k.created_at, "%d/%m/%Y") as consultation_date'), 
					DB::raw('DATE_FORMAT(k.created_at, "%H:%i") as consultation_time'), 
					'j.name as completed_name', 
					DB::raw('DATE_FORMAT(completed_at, "%d/%m/%Y") as completed_date'), 
					DB::raw('DATE_FORMAT(completed_at, "%H:%i") as completed_time'), 
					'l.name as dispensed_name', 
					'dispensed_at',
					'order_completed', 
					'orders.order_id', 
					'cancel_id', 
					'cancel_reason', 
					'post_id', 
					DB::raw('TIMEDIFF(completed_at, orders.created_at) as turnaround'),
					DB::raw('TIMEDIFF(IFNULL(completed_at, now()),orders.created_at) as age'), 
					'order_quantity_supply'
					)
					->leftJoin('encounters as b', 'b.encounter_id', '=', 'orders.encounter_id')
					->leftJoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->leftJoin('products as e', 'e.product_code', '=', 'orders.product_code')
					->leftJoin('users as f', 'f.id', '=', 'orders.user_id')
					->leftJoin('order_cancellations as g', 'g.order_id', '=', 'orders.order_id')
					->leftJoin('ref_patient_types as h', 'h.type_code', '=', 'b.type_code')
					->leftJoin('inventories as i', 'i.order_id', '=', 'orders.order_id')
					->leftJoin('users as j', 'j.id', '=', 'orders.completed_by')
					->leftJoin('consultations as k','k.consultation_id', '=', 'orders.consultation_id')
					->leftJoin('users as l', 'l.id', '=', 'orders.dispensed_by')
					->leftJoin('ref_encounter_types as m', 'm.encounter_code', '=', 'b.encounter_code')
					->orderBy('b.encounter_id');

			if (!empty($request->search)) {
					$orders= $orders->where(function ($query) use ($request) {
						$query->where('patient_name','like','%'.$request->search.'%')
							->orWhere('patient_mrn','like','%'.$request->search.'%')
							->orWhere('b.encounter_id','like','%'.$request->search.'%');
					});
			}

			if (!empty($request->encounter_code)) {
					$orders = $orders->where('b.encounter_code','=',$request->encounter_code);
			}

			if (!empty($request->date_start) && empty($request->date_end)) {
				$orders = $orders->where('orders.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($request->date_start) && !empty($request->date_end)) {
				$orders = $orders->where('orders.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($request->date_start) && !empty($request->date_end)) {
				$orders = $orders->whereBetween('orders.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if (!empty($request->ward_code)) {
					$orders = $orders->where('orders.ward_code','=',$request->ward_code);
			}

			if (!empty($request->product_code)) {
					$orders = $orders->where('e.product_code','=',$request->product_code);
			}

			if (!empty($request->encounter_id)) {
					$orders = $orders->where('orders.encounter_id','=',$request->encounter_id);
			}

			if (!empty($request->category_code)) {
					$orders = $orders->where('e.category_code','=',$request->category_code);
			} else {
					/*
					$category_codes = Auth::user()->categoryCodes();
					if (count($category_codes)>0) {
							$orders = $orders->whereIn('e.category_code',$category_codes);
					}
					 */
			}

			if (!empty($request->user_id)) {
					$orders = $orders->where('orders.user_id','=',$request->user_id);
			}

			if (!empty($request->age)) {
					$orders = $orders->where(DB::raw('TIMEDIFF(now(),orders.created_at)'),">",$request->age);
					//$orders = $orders->having('age','<', $request->age);
			}

			if (!empty($request->status_code)) {
					switch ($request->status_code) {
							case "unposted":
									$orders = $orders->where('post_id','=',0);
									break;
							case "posted":
									$orders = $orders->where('post_id','>',0)
													->whereNull('cancel_id');
									break;
							case "cancel":
									$orders = $orders->whereNotNull('cancel_id');
									break;
							case "completed":
									$orders = $orders->where('order_completed','=',1);
									break;
							case "incomplete":
									$orders = $orders->where('order_completed','=',0);
									break;
							case "reported":
									$orders = $orders->whereNotNull('order_report');
									break;
					}
			}
			
			if ($request->export_report) {
				DojoUtility::export_report($orders->get());
			}

			$orders = $orders->paginate($this->paginateValue);

			$categories = ProductCategory::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('','');
			$status = array(''=>'','posted'=>'Posted','unposted'=>'Unposted','cancel'=>'Cancel','incomplete'=>'Incomplete', 'completed'=>'Completed','reported'=>'Reported');

			return view('orders.enquiry', [
					'orders'=>$orders,
					'search'=>$request->search,
					'ward' => Ward::where('ward_code','<>','mortuary')->orderBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward_code' => $request->ward_code,
					'consultants' => $this->getConsultants(),
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'user_id' => $request->user_id,
					'categories'=>Auth::user()->categoryList(),
					'category_code' => $request->category_code,
					'status'=> $status,
					'status_code' => $request->status_code,
					'product_code' => $request->product_code?:null,
					'age' => $request->age,
					'encounter_id' => $request->encounter_id,
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					'encounter_code'=>$request->encounter_code?:null,
					]);
	}

	public function getConsultants()
	{
			$consultants = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('consultant',1)
							->orderBy('name')
							->lists('name','id')
							->prepend('','');
			return $consultants;
	}

	public function amqp()
	{
				Amqp::pushMessage("lab","Test");
				return "X";
	}

	public function drop($consultation_id)
	{
			$helper = new OrderHelper();
			$helper->dropCharge($consultation_id);
			return "Ok";
	}
}
