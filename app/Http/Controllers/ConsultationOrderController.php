<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ConsultationOrder;
use Log;
use DB;
use Session;
use App\QueueLocation as Location;
use App\Product;

class ConsultationOrderController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($id)
	{
			$consultation_orders = DB::table('orders')
					->where('consultation_id','=',$id)
					->orderBy('created_at')
					->paginate($this->paginateValue);
			return view('consultation_orders.index', [
					'consultation_orders'=>$consultation_orders,
					'consultation_id'=>$id,
			]);
	}

	public function create(Request $request, $consultation_id, $product_code)
	{
			$order = new ConsultationOrder();
			$product = Product::findOrFail($product_code);

			$order->consultation_id = $consultation_id;
			$order->product_code = $product_code;
			
		 	if ($product->order_form == '2') {
				return redirect('/order_drugs/create/'.$consultation_id.'/'.$product_code);
			} else {
				return view('consultation_orders.create', [
					'consultation_order' => $order,
					'consultation_id' => $consultation_id,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
				]);
			}
	}

	public function store(Request $request) 
	{
			$consultation_order = new ConsultationOrder();
			$valid = $consultation_order->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$consultation_order = new ConsultationOrder($request->all());
					$consultation_order->product_code = $request->product_code;
					$consultation_order->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/consultation_orders/'.$consultation_order->consultation_id);
			} else {
					return redirect('/consultation_orders/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$consultation_order = ConsultationOrder::findOrFail($id);
			$product = Product::find($consultation_order->product_code);

		 	if ($product->order_form == '2') {
					return redirect('/order_drugs/'.$consultation_order->orderDrug->id.'/edit');
			} else {
					return view('consultation_orders.edit', [
							'consultation_order'=>$consultation_order,
							'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
							]);
			}
	}

	public function update(Request $request, $id) 
	{
			$consultation_order = ConsultationOrder::findOrFail($id);
			$consultation_order->fill($request->input());
			$consultation_order->order_completed = $request->order_completed ?: 0;
			$consultation_order->order_is_discharge = $request->order_is_discharge ?: 0;

			$valid = $consultation_order->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					Log::info("XXXXX");
					$consultation_order->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultation_orders/'.$consultation_order->consultation_id);
			} else {
					Log::info("XXXXX");
					return view('consultation_orders.edit', [
							'consultation_order'=>$consultation_order,
							'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$consultation_order = ConsultationOrder::findOrFail($id);
		return view('consultation_orders.destroy', [
			'consultation_order'=>$consultation_order
			]);

	}
	public function destroy($id)
	{	
			$consultation_order = ConsultationOrder::findOrFail($id);
			ConsultationOrder::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultation_orders/'.$consultation_order->consultation_id);
	}
	
	public function search(Request $request)
	{
			$consultation_orders = DB::table('orders')
					->where('product_name','like','%'.$request->search.'%')
					->orWhere('product_code', 'like','%'.$request->search.'%')
					->orderBy('product_name')
					->paginate($this->paginateValue);

			return view('consultation_orders.index', [
					'consultation_orders'=>$consultation_orders,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$consultation_orders = DB::table('orders')
					->where('product_code','=',$id)
					->paginate($this->paginateValue);

			return view('consultation_orders.index', [
					'consultation_orders'=>$consultation_orders
			]);
	}
}
