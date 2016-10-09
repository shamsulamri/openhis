<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PurchaseOrderLine;
use Log;
use DB;
use Session;
use App\Product;
use App\PurchaseOrder;

class PurchaseOrderLineController extends Controller
{
	public $paginateValue=100;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($purchase_id)
	{
			/*
			$purchase_order_lines = DB::table('purchase_order_lines as a')
					->leftJoin('products as b', 'b.product_code','=','a.product_code')
					->where('purchase_id','=',$purchase_id)
					->orderBy('purchase_id')
					->paginate($this->paginateValue);
			 */

			$purchase_order = PurchaseOrder::find($purchase_id);

			if ($purchase_order->purchase_received==1) {
				$purchase_order_lines = PurchaseOrderLine::orderBy('purchase_id')
					->where('purchase_id','=',$purchase_id)
					->paginate($this->paginateValue);
			} else {
				$purchase_order_lines = PurchaseOrderLine::withTrashed()
					->orderBy('purchase_id')
					->where('purchase_id','=',$purchase_id)
					->paginate($this->paginateValue);
			}


			return view('purchase_order_lines.index', [
					'purchase_order_lines'=>$purchase_order_lines,
					'purchase_order'=>$purchase_order,
					'purchase_id' => $purchase_id,
			]);
	}

	public function show($purchase_id)
	{
			$purchase_order = PurchaseOrder::find($purchase_id);
			return view('purchase_order_lines.show', [
					'purchase_id' => $purchase_id,
					'purchase_order' => $purchase_order,
			]);
	}
	
	public function create($purchase_id)
	{
			$purchase_order_line = new PurchaseOrderLine();
			$purchase_order_line->purchase_id = $purchase_id;
			return view('purchase_order_lines.create', [
					'purchase_order_line' => $purchase_order_line,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$purchase_order_line = new PurchaseOrderLine();
			$valid = $purchase_order_line->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$purchase_order_line = new PurchaseOrderLine($request->all());
					$purchase_order_line->line_id = $request->line_id;
					$purchase_order_line->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/purchase_order_lines/id/'.$purchase_order_line->line_id);
			} else {
					return redirect('/purchase_order_lines/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$purchase_order_line = PurchaseOrderLine::findOrFail($id);
			return view('purchase_order_lines.edit', [
					'purchase_order_line'=>$purchase_order_line,
					'product' => Product::find($purchase_order_line->product_code),
					]);
	}

	public function update(Request $request, $id) 
	{
			$purchase_order_line = PurchaseOrderLine::findOrFail($id);
			$purchase_order_line->fill($request->input());
			$product = Product::find($purchase_order_line->product_code);

			$valid = $purchase_order_line->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					if ($purchase_order_line->purchaseOrder->purchase_posted==0) {
							$purchase_order_line->line_quantity_received=$purchase_order_line->line_quantity_ordered;
					}
					$purchase_order_line->line_total=$purchase_order_line->line_quantity_ordered*$purchase_order_line->line_price;
					$purchase_order_line->line_total=$purchase_order_line->line_total*(1+($product->product_gst/100));
					if ($purchase_order_line->purchaseOrder->purchase_posted==1) {
							$purchase_order_line->line_total=($purchase_order_line->line_quantity_received+$purchase_order_line->line_quantity_received_2)*$purchase_order_line->line_price;
					}
					$purchase_order_line->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/purchase_order_lines/index/'.$request->purchase_id);
			} else {
					return view('purchase_order_lines.edit', [
							'purchase_order_line'=>$purchase_order_line,
							'product' => Product::find($purchase_order_line->product_code),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$purchase_order_line = PurchaseOrderLine::findOrFail($id);
		$product = Product::find($purchase_order_line->product_code);
		return view('purchase_order_lines.destroy', [
				'purchase_order_line'=>$purchase_order_line,
				'product'=>$product,
			]);

	}
	public function destroy($id)
	{	
			$purchase_order_line = PurchaseOrderLine::findOrFail($id);
			if ($purchase_order_line->purchaseOrder->purchase_posted==0) {
					PurchaseOrderLine::find($id)->forceDelete();
			} else {
					PurchaseOrderLine::find($id)->delete();
			}
			Session::flash('message', 'Record deleted.');
			return redirect('/purchase_order_lines/index/'.$purchase_order_line->purchase_id);
	}
	
	public function search(Request $request)
	{
			$purchase_order_lines = DB::table('purchase_order_lines')
					->where('purchase_id','like','%'.$request->search.'%')
					->orWhere('line_id', 'like','%'.$request->search.'%')
					->orderBy('purchase_id')
					->paginate($this->paginateValue);

			return view('purchase_order_lines.index', [
					'purchase_order_lines'=>$purchase_order_lines,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$purchase_order_lines = DB::table('purchase_order_lines')
					->where('line_id','=',$id)
					->paginate($this->paginateValue);

			return view('purchase_order_lines.index', [
					'purchase_order_lines'=>$purchase_order_lines
			]);
	}
}
