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
use Auth;
use App\OrderInvestigation;
use App\OrderDrug;
use App\DrugPrescription;
use App\DojoUtility;
use App\EncounterHelper;
use App\OrderHelper;
use App\Ward;
use App\StockHelper;
use App\OrderMultiple;
use App\User;
use App\ProductCategory;
use App\AMQPHelper as Amqp;

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
			]);
	}

	public function index(Request $request)
	{
			$encounter= Encounter::find(Session::get('encounter_id'));
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			$fields = ['product_name', 
					'a.product_code', 
					'cancel_id', 
					'a.order_id', 
					'a.user_id', 
					'order_quantity_request',
					'order_quantity_supply',
					'post_id', 
					'd.created_at',
					'order_is_discharge',
					'order_completed',
					'order_report',
					'category_name',
					'product_edit_price',
					];

			$sql_raw= "sum(order_quantity_request) as quantity_total, product_name, a.product_code, cancel_id, a.order_id, a.user_id, post_id, d.created_at,order_is_discharge,order_completed,order_report,category_name,product_edit_price, product_duration_use";


					//->selectRaw($sql_raw)
					//->groupBy('a.product_code')
			$orders = DB::table('orders as a')
					->select($fields)
					->join('products as b','a.product_code','=','b.product_code')
					->leftjoin('order_cancellations as c', 'c.order_id', '=', 'a.order_id')
					->leftjoin('consultations as d', 'd.consultation_id', '=', 'a.consultation_id')
					->leftjoin('product_categories as e', 'e.category_code', '=', 'b.category_code')
					->where('a.encounter_id','=',$encounter->encounter_id)
					->orderBy('b.category_code')
					->orderBy('a.created_at', 'desc');

			//if (Auth::user()->authorization->module_support != 1) {
					$orders = $orders->where('a.user_id','=',Auth::user()->id);
			//}

			if ($encounter->admission) {
				$orders = $orders->where('ward_code', $encounter->admission->bed->ward_code);
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

			$orders = $orders->paginate($this->paginateValue);

			return view('orders.index', [
					'orders'=>$orders,
					'consultation'=>$consultation,
					'patient'=>$encounter->patient,
					'tab'=>'order',
					'consultOption' => 'consultation',
					'dojo'=> new DojoUtility(),
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

		 	if ($product->order_form == '2') {
					return redirect('/order_drugs/'.$order->orderDrug->id.'/edit?order_single='.$request->order_single);
			} elseif ($product->order_form == '3') {
					return redirect('/order_investigations/'.$order->orderInvestigation->id.'/edit?order_single='.$request->order_single);
			} else {
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
					]);
			}
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
			OrderMultiple::where('order_id', $id)->delete();
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
			$product_code = $request->product_code;
			$encounter_id = Session::get('encounter_id');
			$orders = Order::select('orders.order_id')
						->where('product_code','=', $product_code)
						->leftJoin('order_cancellations as b', 'b.order_id', '=', 'orders.order_id')
						->where('encounter_id','=', $encounter_id)
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
											OrderMultiple::where('order_id', $id)->delete();
											Order::find($id)->delete();
									}
							}
					}
			}
	}

	public function addOrder(Request $request)
	{
			$product_code = $request->product_code;
			Log::info($product_code);
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
													OrderMultiple::where('order_id', $id)->delete();
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
			return $order_id;
	}

	public function single(Request $request, $product_code)
	{
			//$helper = new StockHelper();
			//$batches = $helper->getBatches($product_code, null, 'consumable');
			//return $batches;

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
							Session::flash('message', 'Product already in the order list.');
							return redirect('/order_product/search?search='.$request->_search.'&set_code='.$request->_set_value.'&page='.$request->_page);
					}
			}

			$product = Product::find($product_code);
			$order_id = OrderHelper::orderItem($product, $request->cookie('ward'));
			if ($order_id>0) {
				return redirect('/order_product/search?search='.$request->_search.'&set_code='.$request->_set_value.'&page='.$request->_page.'&order_id='.$order_id.'&categories='.$request->category_code);
			} else {
				return redirect('/order_product/search?search='.$request->_search.'&set_code='.$request->_set_value.'&page='.$request->_page);

			}
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
			]);
	}

	public function enquiry(Request $request)
	{
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$orders = Order::select('b.encounter_id', 'e.product_code', 'product_name', 'orders.order_id', 'order_completed', 'patient_name', 'patient_mrn', 'orders.created_at as order_date', 'name', 'cancel_id', 'cancel_reason', 'post_id', 'order_report', DB::raw('TIMEDIFF(completed_at, orders.created_at) as turnaround'),DB::raw('TIMEDIFF(IFNULL(completed_at, now()),orders.created_at) as age'), 'order_quantity_supply')
					->leftJoin('encounters as b', 'b.encounter_id', '=', 'orders.encounter_id')
					->leftJoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->leftJoin('products as e', 'e.product_code', '=', 'orders.product_code')
					->leftJoin('users as f', 'f.id', '=', 'orders.user_id')
					->leftJoin('order_cancellations as g', 'g.order_id', '=', 'orders.order_id')
					->orderBy('b.encounter_id');

			if (!empty($request->search)) {
					$orders= $orders->where(function ($query) use ($request) {
						$query->where('patient_name','like','%'.$request->search.'%')
							->orWhere('patient_mrn','like','%'.$request->search.'%')
							->orWhere('b.encounter_id','like','%'.$request->search.'%');
					});
			}


			Log::info("----");
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
					'age' => $request->age,
					'encounter_id' => $request->encounter_id,
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
