<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderMaintenance;
use Log;
use DB;
use Session;
use App\Ward;
use App\QueueLocation as Location;
use App\Store;
use App\Product;
use App\BillMaterial as Bom;
use App\UnitMeasure as Unit;


class OrderMaintenanceController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$orders = OrderMaintenance::orderBy('product_code')
					->paginate($this->paginateValue);

			return view('order_maintenances.index', [
					'orders'=>$orders,
					'encounter_id'=>null,
					'order_id'=>null,
					'product_code'=>null,
			]);
	}

	public function create()
	{
			$order = new OrderMaintenance();
			return view('order_maintenances.create', [
					'order' => $order,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'bom' => Bom::all()->sortBy('bom_name')->lists('bom_name', 'bom_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$order = new OrderMaintenance();
			$valid = $order->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order = new OrderMaintenance($request->all());
					$order->order_id = $request->order_id;
					$order->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/order_maintenances/id/'.$order->order_id);
			} else {
					return redirect('/order_maintenances/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order = OrderMaintenance::findOrFail($id);
			return view('order_maintenances.edit', [
					'order'=>$order,
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'bom' => Bom::all()->sortBy('bom_name')->lists('bom_name', 'bom_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$order = OrderMaintenance::findOrFail($id);
			$order->fill($request->input());

			$order->order_completed = $request->order_completed ?: 0;
			$order->order_multiple = $request->order_multiple ?: 0;
			$order->order_is_discharge = $request->order_is_discharge ?: 0;
			$order->order_is_future = $request->order_is_future ?: 0;
			$order->order_include_stat = $request->order_include_stat ?: 0;

			$valid = $order->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_maintenances/id/'.$id);
			} else {
					return redirect('/order_maintenances/'.$id.'/edit')
							->withErrors($valid)
							->withInput();
			}
	}
	
	public function delete($id)
	{
		$order = OrderMaintenance::findOrFail($id);
		return view('order_maintenances.destroy', [
			'order'=>$order
			]);

	}
	public function destroy($id)
	{	
			OrderMaintenance::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_maintenances');
	}
	
	public function search(Request $request)
	{
			$orders = OrderMaintenance::orderBy('product_code');

			if ($request->encounter_id) {
					$orders = $orders->where('encounter_id', $request->encounter_id);
			}

			if ($request->order_id) {
					$orders = $orders->where('order_id', $request->order_id);
			}

			if ($request->product_code) {
					$orders = $orders->where('product_code', $request->product_code);
			}

			$orders = $orders->paginate($this->paginateValue);

			return view('order_maintenances.index', [
					'orders'=>$orders,
					'encounter_id'=>$request->encounter_id,
					'order_id'=>$request->order_id,
					'product_code'=>$request->product_code,
					]);
	}

	public function searchById($id)
	{
			$orders = OrderMaintenance::where('order_id','=',$id)
					->paginate($this->paginateValue);

			return view('order_maintenances.index', [
					'orders'=>$orders,
					'encounter_id'=>null,
					'order_id'=>$id,
					'product_code'=>null,
			]);
	}
}
