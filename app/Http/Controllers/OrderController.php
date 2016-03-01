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


class OrderController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$orders = DB::table('orders')
					->orderBy('product_code')
					->paginate($this->paginateValue);
			return view('orders.index', [
					'orders'=>$orders
			]);
	}

	public function create(Request $request)
	{
			$order = new Order();

			if (empty($request->consult_id)==false) {
					$order->consult_id = $request->consult_id;
			}

			return view('orders.create', [
					'order' => $order,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					]);
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
					return redirect('/orders/id/'.$order->order_id);
			} else {
					return redirect('/orders/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order = Order::findOrFail($id);
			return view('orders.edit', [
					'order'=>$order,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					]);
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
					return redirect('/orders/id/'.$id);
			} else {
					return view('orders.edit', [
							'order'=>$order,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
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
			Order::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/orders');
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
