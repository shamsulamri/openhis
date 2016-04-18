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
use Carbon\Carbon;
use Log;

class PurchaseOrderController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$purchase_orders = DB::table('purchase_orders as a')
					->leftJoin('suppliers as b', 'b.supplier_code','=','a.supplier_code')
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
					'maxYear' => Carbon::now()->year,
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
			if ($purchase_order->purchase_posted==1) {
					$purchase_order->receive_datetime = date('d/m/Y H:i', strtotime(Carbon::now())); 
					//$purchase_order->receive_datetime = '03/03/2016 10:33';
					//return $purchase_order->receive_datetime;
			}
			return view('purchase_orders.edit', [
					'purchase_order'=>$purchase_order,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'maxYear' => Carbon::now()->year,
					]);
	}

	public function update(Request $request, $id) 
	{
			$purchase_order = PurchaseOrder::findOrFail($id);
			$purchase_order->fill($request->input());

			$valid = $purchase_order->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$purchase_order->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/purchase_order_lines/index/'.$id);
			} else {
					return view('purchase_orders.edit', [
							'purchase_order'=>$purchase_order,
							'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
							'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
							'maxYear' => Carbon::now()->year,
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
			$purchase_orders = DB::table('purchase_orders as a')
					->where('purchase_id','=',$id)
					->leftJoin('suppliers as b', 'b.supplier_code','=','a.supplier_code')
					->orderBy('purchase_date')
					->paginate($this->paginateValue);

			return view('purchase_orders.index', [
					'purchase_orders'=>$purchase_orders,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
			]);
	}

	public function post(Request $request)
	{
		$purchase_order = PurchaseOrder::findOrFail($request->purchase_id);
		
		return view('purchase_orders.post', [
			'purchase_order'=>$purchase_order
			]);
	}

	public function postVerify(Request $request)
	{
		$purchase_order = PurchaseOrder::findOrFail($request->purchase_id);
		$purchase_order->purchase_posted=1;
		$purchase_order->save();

		return redirect('/purchase_order_lines/index/'.$request->purchase_id);
	}
}
