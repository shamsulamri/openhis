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
use App\Consultation;
use App\Order;
use App\OrderMultiple;
use App\OrderHelper;
use App\Product;
use App\EncounterHelper;
use Auth;
use App\OrderImaging;
use App\Encounter;

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

	public function imaging()
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$encounter = $consultation->encounter;
			$params = DB::table('order_imaging')
						->get();

			/*
			$procedures = Product::where('category_code', 'radiography')
								->orderBy('product_name')
								->lists('product_name', 'product_code');
			 */
			$procedures = OrderImaging::orderBy('product_name')
							->leftjoin('products as b', 'b.product_code', '=', 'order_imaging.product_code')
							->lists('product_name', 'b.product_code');
			
			$orders = Order::where('consultation_id', $consultation->consultation_id)
						->leftJoin('products as a', 'a.product_code', '=', 'orders.product_code')
						->where('category_code', 'imaging')
						->get();

			$sides = [''];
			$regions = [''];
			$views = [''];

			return view('order_investigations.imaging', [
					'consultation'=>$consultation,
					'sides'=>$sides,
					'regions'=>$regions,
					'views'=>$views,
					'procedures'=>$procedures,
					'params'=>$params,
					'orders'=>$orders,
			]);
	}

	public function deleteImaging($id)
	{	
			Order::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/imaging');
	}

	public function addImaging(Request $request)
	{

			$product = Product::find($request->product_code);
			if ($product) {
					$order_id = OrderHelper::orderItem($product, $request->cookie('ward'));
					$order = Order::find($order_id);
					$description = "";
					if ($request->side) $description .= $request->side." > ";
					if ($request->region) $description .= $request->region." > ";
					if ($request->views) $description .= $request->views;
					$order->order_description = $description;
					$order->save();
			}

			Session::flash('message', 'Record successfully created.');
			return redirect('/imaging');
	}

	public function create($product_code)
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$order_investigation = new OrderInvestigation();
			$order_investigation->investigation_date = date('d/m/Y');
					
			$product = DB::table('products')
						->select('product_name','product_code')
						->where('product_code','=',$product_code)->get();
		 
			return view('order_investigations.create', [
					'order_investigation' => $order_investigation,
					'urgency' => Urgency::all()->sortBy('urgency_name')->lists('urgency_name', 'urgency_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'consultation' => $consultation,
					'patient'=>$consultation->encounter->patient,
					'product' => $product,
					'tab' => 'order',
					'consultOption' => 'consultation',
					'order' => new Order(), 
					]);
	}

	public function store(Request $request) 
	{
			$order_investigation = new OrderInvestigation();
			$valid = $order_investigation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product = Product::find($request->product_code);
					$order = new Order();
					$order->fill($request->input());
					$order->consultation_id = Session::get('consultation_id');
					$order->encounter_id = Session::get('encounter_id');
					$order->user_id = Auth::user()->id;
					$order->product_code = $request->product_code;
					$order->order_is_discharge = $request->order_is_discharge;
					$order->order_description = $request->order_description;
					$order->location_code = $product->location_code;
					$order->save();
					
					$order_investigation = new OrderInvestigation($request->all());
					$order_investigation->order_id = $order->order_id;
					$order_investigation->save();

					Session::flash('message', 'Record successfully created.');
					return redirect('/orders');
			} else {
					return redirect('/order_investigations/create/'.$request->consultation_id.'/'.$request->product_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit(Request $request, $id) 
	{
			$order_investigation = OrderInvestigation::findOrFail($id);
			$consultation = Consultation::find($order_investigation->order->consultation_id);

			$product = DB::table('products')
						->select('product_name','product_code')
						->where('product_code','=',$order_investigation->order->product_code)->first();
			
			return view('order_investigations.edit', [
					'order_investigation'=>$order_investigation,
					'urgency' => Urgency::all()->sortBy('urgency_name')->lists('urgency_name', 'urgency_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'consultation' => $consultation,
					'patient'=>$consultation->encounter->patient,
					'product' => $product,
					'tab' => 'order',
					'consultOption' => 'consultation',
					'order' => $order_investigation->order,
					'order_single'=>$request->order_single,
					]);
	}

	public function editDate($id) 
	{
			$order_investigation = OrderInvestigation::findOrFail($id);
			$consultation = Consultation::find($order_investigation->order->consultation_id);

			$product = DB::table('products')
						->select('product_name','product_code')
						->where('product_code','=',$order_investigation->order->product_code)->get();
			
			return view('order_investigations.edit_date', [
					'order_investigation'=>$order_investigation,
					'urgency' => Urgency::all()->sortBy('urgency_name')->lists('urgency_name', 'urgency_code')->prepend('',''),
					'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
					'consultation' => $consultation,
					'patient'=>$consultation->encounter->patient,
					'product' => $product,
					'tab' => 'order',
					'consultOption' => 'consultation',
					'order' => $order_investigation->order,
					]);
	}

	public function updateDate(Request $request) {
			$order_investigation = OrderInvestigation::findOrFail($request->id);
			$order_investigation->fill($request->input());
			$order_investigation->save();
			Session::flash('message', 'Record successfully updated.');
			return redirect('/futures');
	}

	public function update(Request $request, $id) 
	{
			$order_investigation = OrderInvestigation::findOrFail($id);
			$order_investigation->fill($request->input());
			$order_investigation->investigation_recur = $request->investigation_recur ?: 0;

			if (!empty($order_investigation->period->period_mins) && !empty($order_investigation->frequency->frequency_mins)) {
					$frequencies = $order_investigation->period->period_mins/$order_investigation->frequency->frequency_mins;
			}

			$order = Order::find($order_investigation->order_id);
			$order->fill($request->input());
			$order->order_is_discharge = $request->order_is_discharge ?: 0;
			$order->order_completed = $request->order_completed ?: 0;
			if ($order_investigation->investigation_date>date('Y/m/d')) {
					$order->order_is_future = 1;
			} else {
					$order->order_is_future = 0;
			}

			if ($request->order_completed == 1) {
				$location_code = $request->cookie('queue_location');
				$admission = EncounterHelper::getCurrentAdmission($order->encounter_id);
				$order->location_code = $location_code;

				if ($order->product->product_stocked==1) {
						$store_code = OrderHelper::getLocalStore($order->consultation->encounter, $admission);
						$order->store_code = $store_code;
				}
			}

			$order->order_quantity_supply = $order->order_quantity_request;
			$order->save();
			
			$valid = $order_investigation->validate($request->all(), $request->_method);	
			$valid2 = $order->validate($request->all(), $request->_method);	

			if ($valid->passes() && $valid2->passes()) {
					$order_investigation->save();
					OrderHelper::createInvestigationOrders($order_investigation);

					$order = Order::find($order_investigation->order_id);
					$order->post_id=0;
					$order->save();

					Session::flash('message', 'Record successfully updated.');
					return redirect('/orders');
			} else {
					$consultation = Consultation::find($order_investigation->order->consultation_id);
					
					$product = DB::table('products')
								->select('product_name','product_code')
								->where('product_code','=',$order_investigation->order->product_code)->get();
					return view('order_investigations.edit', [
							'order_investigation'=>$order_investigation,
							'urgency' => Urgency::all()->sortBy('urgency_name')->lists('urgency_name', 'urgency_code')->prepend('',''),
							'period' => Period::all()->sortBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
							'frequency' => Frequency::all()->sortBy('frequency_name')->lists('frequency_name', 'frequency_code')->prepend('',''),
							'consultation' => $consultation,
							'patient'=>$consultation->encounter->patient,
							'product' => $product,
							'tab' => 'order',
							'consultOption' => 'consultation',
							'order' => $order_investigation->order,
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
