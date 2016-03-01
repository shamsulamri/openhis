<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PurchaseOrder;
use DB;
use Session;
use App\Supplier;
use App\Store;
use Auth;
use App\DojoUtility as dj;

class PurchaseOrderController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$purchase_orders = DB::table('purchase_orders')
					->orderBy('purchase_date')
					->paginate($this->paginateValue);
			return view('purchase_orders.index', [
					'purchase_orders'=>$purchase_orders
			]);
	}

	public function create()
	{
			$purchase_order = new PurchaseOrder();

			return view('purchase_orders.create', [
					'purchase_order' => $purchase_order,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$purchase_order = new PurchaseOrder();
			$purchase_order->user_id = Auth::user()->id;

			$valid = $purchase_order->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$purchase_order = new PurchaseOrder($request->all());
					$purchase_order->purchase_id = $request->purchase_id;
					$purchase_order->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/purchase_orders/id/'.$purchase_order->purchase_id);
			} else {
					return redirect('/purchase_orders/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$purchase_order = PurchaseOrder::findOrFail($id);
			return view('purchase_orders.edit', [
					'purchase_order'=>$purchase_order,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			dj::logout("-->".dj::validateDate($request->purchase_date));
			$purchase_order = PurchaseOrder::findOrFail($id);
			$purchase_order->fill($request->input());

			$purchase_order->purchase_posted = $request->purchase_posted ?: 0;
			$purchase_order->purchase_received = $request->purchase_received ?: 0;

			$valid = $purchase_order->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$purchase_order->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/purchase_orders/id/'.$id);
			} else {
					return view('purchase_orders.edit', [
							'purchase_order'=>$purchase_order,
							'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
							'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$purchase_order = PurchaseOrder::findOrFail($id);
		return view('purchase_orders.destroy', [
			'purchase_order'=>$purchase_order
			]);

	}
	public function destroy($id)
	{	
			PurchaseOrder::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/purchase_orders');
	}
	
	public function search(Request $request)
	{
			$purchase_orders = DB::table('purchase_orders')
					->where('purchase_date','like','%'.$request->search.'%')
					->orWhere('purchase_id', 'like','%'.$request->search.'%')
					->orderBy('purchase_date')
					->paginate($this->paginateValue);

			return view('purchase_orders.index', [
					'purchase_orders'=>$purchase_orders,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$purchase_orders = DB::table('purchase_orders')
					->where('purchase_id','=',$id)
					->paginate($this->paginateValue);

			return view('purchase_orders.index', [
					'purchase_orders'=>$purchase_orders
			]);
	}
}
