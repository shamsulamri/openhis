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
use App\Store;
use App\StockHelper;
use App\DojoUtility;

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
	
	public function json($purchase_id)
	{
			$poline = PurchaseOrderLine::where('purchase_id', '=', $purchase_id)->get();
			return $poline;
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
					$purchase_order_line->line_total=$purchase_order_line->line_quantity_ordered*$purchase_order_line->line_price;
					$purchase_order_line->line_total_gst= $purchase_order_line->line_total;
					if (!empty($product->purhcase_tax_code)) {
							$purchase_order_line->line_gst=$purchase_order_line->line_total_gst*(1+($product->purchase_tax->tax_rate/100));
					}
					$purchase_order_line->save();

					$product->product_purchase_price = $purchase_order_line->line_price;
					$product->save();

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

	public function stockReceiveLine($id) 
	{
			$purchase_order = PurchaseOrder::find($id);
			$purchase_order_lines = PurchaseOrderLine::where('purchase_id',$id)
										->select('line_id')
										->get();

			$ids = $purchase_order_lines->pluck('line_id');
			$purchase_receives = PurchaseOrderLine::whereIn('line_id', $ids)->get();

			return view('purchase_order_lines.receive', [
					'purchase_order'=>$purchase_order,
					'purchase_receives'=>$purchase_receives,
					'stock_helper'=>new StockHelper,
					'store' => Store::where('store_receiving','=',1)->orderBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
			]);
	}

	public function stockReceivePost(Request $request, $id)
	{
			$valid = array();
			$stock_helper = new StockHelper();
			$purchase_order_lines = PurchaseOrderLine::where('purchase_id',$id)
										->select('line_id')
										->get();

			if ($request->count_completed==0) {
					if (empty($request->store_code)) {
						$valid['store_code']='This field is required.';
					}

					if (empty($request->invoice_number)) {
						$valid['invoice_number']='This field is required.';
					}
			}

			$valid = array_merge($valid, $this->validateStockReceive($request, $id));

			if (!empty($valid)) {
					return redirect('/purchase_order_line/receive/'.$id)
							->withErrors($valid)
							->withInput();
			} 

			foreach($purchase_order_lines as $line) {

				if ($request[$line->line_id]==1) {

					$receive_name = "receive_quantity_".$line->line_id;
					$batch_name = "batch_number_".$line->line_id;
					$expiry_name = "expiry_date_".$line->line_id;

					$stock_helper->stockReceive($line->line_id,
							$request[$receive_name], 
							$request->store_code, 
							$request[$batch_name], 
							$request->delivery_number, 
							$request->invoice_number, 
							$request[$expiry_name]);


				}
			}

			if ($request->close_purchase_order=='1') {
						$purchase_order = PurchaseOrder::find($id);
						$purchase_order->purchase_received=1;
						$purchase_order->purchase_close = DojoUtility::now();
						$purchase_order->save();
						Session::flash('message', 'Record successfully created.');
						return redirect('/purchase_orders');
			}

			Session::flash('message', 'Record successfully created.');
			return redirect('/purchase_order_line/receive/'.$id);
	}

	public function validateStockReceive(Request $request, $purchase_id)
	{
			$stock_helper = new StockHelper();
			$valid= array();

			$purchase_order_lines = PurchaseOrderLine::where('purchase_id',$purchase_id)
										->get();

			foreach($purchase_order_lines as $line) {

					$quantity_order = $line->line_quantity_ordered;
					$total_receive = $stock_helper->stockReceiveSum($line->line_id);
					$total_receive = $total_receive/$line->product->product_conversion_unit;
					$receive_name = "receive_quantity_".$line->line_id;
					$quantity_receive = $request[$receive_name];

					$balance = $quantity_order-$total_receive;
					Log::info('->'.$balance);	

					if ($quantity_receive > $balance) {
						$valid['line_'.$line->line_id]='Quantity receive greater than order.';
					}
			}

			return $valid;
	}
}
