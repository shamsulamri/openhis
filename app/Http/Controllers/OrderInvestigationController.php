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
use App\Product;
use Auth;

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
					$order->order_sale_price = $product->product_sale_price;
					$order->location_code = $product->location_code;
					$order->save();
					
					$order_investigation = new OrderInvestigation($request->all());
					$order_investigation->order_id = $order->order_id;
					$order_investigation->save();
					Log::info($order_investigation->investigation_date);

					Session::flash('message', 'Record successfully created.');
					return redirect('/orders');
			} else {
					return redirect('/order_investigations/create/'.$request->consultation_id.'/'.$request->product_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$order_investigation = OrderInvestigation::findOrFail($id);

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
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_investigation = OrderInvestigation::findOrFail($id);
			$order_investigation->fill($request->input());
			$order_investigation->investigation_recur = $request->investigation_recur ?: 0;

			$order = Order::find($order_investigation->order_id);
			$order->fill($request->input());
			$order->order_is_discharge = $request->order_is_discharge ?: 0;
			$order->save();
			
			$valid = $order_investigation->validate($request->all(), $request->_method);	
			$valid2 = $order->validate($request->all(), $request->_method);	

			if ($valid->passes() && $valid2->passes()) {
					$order_investigation->save();
					

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
