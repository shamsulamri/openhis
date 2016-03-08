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

class OrderController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($consultation_id)
	{
			$consultation = Consultation::findOrFail($consultation_id);

			$orders = DB::table('orders as a')
					->select('product_name', 'a.product_code', 'cancel_id', 'a.order_id', 'order_posted', 'a.created_at')
					->join('products as b','a.product_code','=','b.product_code')
					->leftjoin('order_cancellations as c', 'c.order_id', '=', 'a.order_id')
					->leftjoin('consultations as d', 'd.consultation_id', '=', 'a.consultation_id')
					->where('encounter_id','=',$consultation->encounter_id)
					->orderBy('a.created_at', 'desc')
					->paginate($this->paginateValue);


			return view('orders.index', [
					'orders'=>$orders,
					'consultation'=>$consultation,
					'tab'=>'order',
			]);
	}

	
	public function create($consultation_id, $product_code)
	{
			$order = new Order();
			$order->consultation_id = $consultation_id;
			$order->product_code = $product_code;
			$order->order_quantity_request=1;

			$product=Product::find($product_code);

			$consultation = Consultation::findOrFail($consultation_id);

		 	if ($product->order_form == '2') {
				return redirect('/order_drugs/create/'.$consultation_id.'/'.$product_code);
			} else {
				return view('orders.create', [
					'order' => $order,
					'consultation'=>$consultation,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'tab'=>'order',
					'product'=>$product,
					]);
			}
	}

	public function store(Request $request) 
	{
			$order = new Order();
			$valid = $order->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order = new Order($request->all());
					$order->order_id = $request->order_id;
					$order->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/orders/'.$order->consultation_id);
			} else {
					return redirect('/orders/create/'.$request->consultation_id.'/'.$request->product_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order = Order::findOrFail($id);

			$product = Product::find($order->product_code);
			$consultation = Consultation::findOrFail($order->consultation_id);

		 	if ($product->order_form == '2') {
					return redirect('/order_drugs/'.$order->orderDrug->id.'/edit');
			} else {
				return view('orders.edit', [
					'order'=>$order,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'consultation' => $consultation,
					'tab'=>'order',
					'product'=>$product,
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

			if ($valid->passes()) {
					$order->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/orders/'.$order->consultation_id);
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
		$order = Order::findOrFail($id);
		return view('orders.destroy', [
			'order'=>$order
			]);

	}
	public function destroy($id)
	{	
			$order = Order::find($id);
			Order::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/orders/'.$order->consultation_id);
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
}
