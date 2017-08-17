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
				'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
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
					'post_id', 
					'd.created_at',
					'order_is_discharge',
					'order_completed',
					'order_report',
					'category_name',
					'product_drop_charge',
					];

			$orders = DB::table('orders as a')
					->select($fields)
					->join('products as b','a.product_code','=','b.product_code')
					->leftjoin('order_cancellations as c', 'c.order_id', '=', 'a.order_id')
					->leftjoin('consultations as d', 'd.consultation_id', '=', 'a.consultation_id')
					->leftjoin('product_categories as e', 'e.category_code', '=', 'b.category_code')
					->where('a.encounter_id','=',Session::get('encounter_id'))
					->orderBy('b.category_code')
					->orderBy('a.created_at', 'desc');

			if (!empty(Auth::user()->authorization->location_code)) {
				$location_code = Auth::user()->authorization->location_code;
				$orders = $orders->where('a.location_code','=', $location_code);
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
			$order->order_total = $product->product_sale_price*$order->order_quantity_request;
			$order->location_code = $product->location_code;

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
					$order->order_sale_price = $product->product_sale_price;
					$order->user_id = Auth::user()->id;

					if ($product->product_drop_charge==1) {
							$ward_code = $request->cookie('ward');
							$ward = Ward::where('ward_code', $ward_code)->first();
							$order->store_code = $ward->store_code;
							$order->order_completed = 1;
							$helper = new StockHelper();
							$helper->updateAllStockOnHand($order->product_code);
					}

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
			Log::info($request);
			$order = Order::find($id);
			$order->order_diagnostic_report = $request->report;
			$order->save();
	}

	public function edit($id) 
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
					return redirect('/order_drugs/'.$order->orderDrug->id.'/edit');
			} elseif ($product->order_form == '3') {
					return redirect('/order_investigations/'.$order->orderInvestigation->id.'/edit');
			} else {
				return view('orders.edit', [
					'order'=>$order,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'consultation' => $consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'order',
					'product'=>$product,
					'current_id'=>$current_id,
					]);
			}
	}

	public function update(Request $request, $id) 
	{
			$order = Order::findOrFail($id);
			$order->fill($request->input());

			$order->order_completed = $request->order_completed ?: 0;
			$order->order_is_discharge = $request->order_is_discharge ?: 0;

			$valid = $order->validate($request->all(), $request->_method);	

			$product = Product::find($order->product_code);

			if ($valid->passes()) {
					if ($product->product_drop_charge==1) {
							$ward_code = $request->cookie('ward');
							$ward = Ward::where('ward_code', $ward_code)->first();
							$order->store_code = $ward->store_code;
							$order->order_completed = 1;
							$helper = new StockHelper();
							$helper->updateAllStockOnHand($order->product_code);
					}
					$order->order_quantity_supply = $order->order_quantity_request;
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
				Log::info("save !!!!!");
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

	public function single(Request $request, $product_code)
	{
			$product = Product::find($product_code);
			$order_id = OrderHelper::orderItem($product, $request->cookie('ward'));
			//Session::flash('message', 'Product added to order list.');
			return redirect('/order_product/search?search='.$request->_search.'&set_code='.$request->_set_value.'&page='.$request->_page.'&order_id='.$order_id);
	}

	public function make()
	{
			$encounter= Encounter::find(Session::get('encounter_id'));
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));
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

			return view('orders.make', [
					'orders'=>$orders,
					'consultation'=>$consultation,
					'patient'=>$encounter->patient,
					'encounter'=>$encounter,
					'tab'=>'order',
					'consultOption' => 'consultation',
			]);
	}

}
