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
use App\Stock;
use Auth;
use App\DojoUtility;
use App\StockInput;
use App\StockInputLine;
use App\PurchaseOrderHelper;

class PurchaseOrderLineController extends Controller
{
	public $paginateValue=100;
	public $order_status = array(''=>'','open'=>'Open','posted'=>'Posted','close'=>'Close');

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
					'order_status'=> $this->order_status,
					'status_code'=>null,
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
					if (!empty($product->purchase_tax_code)) {
							$purchase_order_line->line_total_gst=$purchase_order_line->line_total_gst*(1+($product->purchase_tax->tax_rate/100));
							$purchase_order_line->tax_rate = $product->purchase_tax->tax_rate;
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
										->get();

			$stock_input = StockInput::where('purchase_id',$id)
					->where('input_close',0)
					->first();
			if ($stock_input) {
					return redirect('stock_inputs/show/'.$stock_input->input_id);
			}

			$stock_input = new StockInput();
			$stock_input->user_id = Auth::user()->id;
			$stock_input->move_code = 'receive';
			$stock_input->store_code = 'main';
			$stock_input->purchase_id = $id;
			$stock_input->input_description = "Purchase Order Identification:".$id;
			$stock_input->save();

			$stock_helper = new StockHelper();

			foreach($purchase_order_lines as $line) {
				$total_receive = $stock_helper->stockReceiveSum($line->line_id);

				$poline = PurchaseOrderLine::find($line->line_id);

				$input_line = new StockInputLine();
				$input_line->input_id = $stock_input->input_id;
				$input_line->po_line_id = $line->line_id;
				$input_line->product_code = $line->product_code;
				//$input_line->line_value = $line->line_total;
				$input_line->line_quantity = $line->line_quantity_ordered-$total_receive;
				$input_line->line_value = $poline->line_price*$input_line->line_quantity;
				$input_line->save();
			}

			return redirect('stock_inputs/show/'.$stock_input->input_id);
	}

	public function stockReceiveLine2($id) 
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
										->select('line_id','product_code','line_total')
										->get();

			//if ($request->count_completed==0) {
					if (empty($request->store_code)) {
						$valid['store_code']='This field is required.';
					}

					if (empty($request->invoice_number)) {
						$valid['invoice_number']='This field is required.';
					}
			//}

			// Validate batch number 
		
			foreach($purchase_order_lines as $line) {
					if ($request['track_batch_'.$line->line_id]==1) {
							$batch_name = "batch_number_".$line->line_id;
							if (empty($request[$batch_name])) {
								$valid[$batch_name]='This field is required.';
							}
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
					$expiry_date = "expiry_date_".$line->line_id;

					/**
					Log::info("------>".$expiry_date);
					$stock_helper->stockReceive($line->line_id,
							$request[$receive_name], 
							$request->store_code, 
							$request[$batch_name], 
							$request->delivery_number, 
							$request->invoice_number, 
							$request[$expiry_date]);
							**/

						$stock_quantity = $request[$receive_name];
						if ($line->product->product_conversion_unit>0) {
								$stock_quantity = $stock_quantity*$line->product->product_conversion_unit;	
						}


						$stock = new Stock();
						$stock->username = Auth::user()->username;
						$stock->move_code = 'receive';
						$stock->store_code = $request->store_code;
						$stock->product_code = $line->product_code;
						$stock->stock_quantity = $stock_quantity;
						$stock->stock_value = $line->line_total;
						$stock->batch_number = $request[$batch_name];
						$stock->stock_datetime = DojoUtility::now();
						$stock->stock_description = "Purchase id: ".$line->purchase_id;
						$stock->invoice_number = $request->invoice_number;

						$stock = $stock_helper->moveStock($stock);


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
			//return redirect('/purchase_order_line/receive/'.$id);
			return redirect('/purchase_orders');
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

	public function enquiry(Request $request)
	{
		$lines = StockInputLine::select('line_id', 'purchase_order_number','purchase_date', 'c.created_at', 'invoice_number', 'product_name', 
					'e.product_code', 'purchase_posted', 'purchase_received', 'line_quantity','line_value')
				->leftJoin('stock_receives as b', 'b.input_id','=', 'stock_input_lines.input_id')
				->leftJoin('stock_inputs as c', 'c.input_id', '=', 'b.input_id')
				->leftJoin('purchase_orders as d', 'd.purchase_id', '=', 'c.purchase_id')
				->leftJoin('products as e', 'e.product_code', '=', 'stock_input_lines.product_code')
				->whereNotNull('po_line_id');

		if (!empty($request->document_number)) {
				$lines = $lines->where('purchase_order_number','=',$request->document_number);
				$lines = $lines->orWhere('invoice_number','=',$request->document_number);
		}

		if (!empty($request->search)) {
				$lines = $lines->where('product_name','like','%'.$request->search.'%');
		}

		/*** Category ****/
		$category_codes = Auth::user()->categoryCodes();
		if (count($category_codes)>0) {
			$lines = $lines->whereIn('e.category_code',$category_codes);
		}

		/*** Status ***/
		if ($request->status_code != '') {
				switch($request->status_code) {
						case "posted":
								$lines = $lines->where('purchase_posted','=', 1 );
								$lines = $lines->where('purchase_received','=', 0 );
								break;
						case "close":
								$lines = $lines->where('purchase_posted','=', 1 );
								$lines = $lines->where('purchase_received','=', 1 );
								break;
						case "open":
								$lines = $lines->where('purchase_posted','=', 0 );
								$lines = $lines->where('purchase_received','=', 0 );
								break;
				} 
		}

		/*** Date Range ****/
		$date_start = DojoUtility::dateWriteFormat($request->date_start);
		$date_end = DojoUtility::dateWriteFormat($request->date_end);

		if (isset($date_start) & isset($date_end)) {
			$lines = $lines->whereBetween('purchase_date', [$date_start.' 00:00', $date_end.' 23:59']);
		}	

		if (isset($date_start) & !isset($date_end)) {
			$lines = $lines->where('purchase_date', ">=", $date_start.' 00:00');
		}	

		if (!isset($date_start) & isset($date_end)) {
			$lines = $lines->where('purchase_date', "<=", $date_end.' 23:59');
		}	

		$lines = $lines->orderBy('purchase_date', 'invoice_number');

		if ($request->export_report) {
				DojoUtility::export_report($lines->get());
		}

		$lines = $lines->paginate($this->paginateValue);

		return view('purchase_order_lines.enquiry', [
				'store'=>Auth::user()->storeList()->prepend('',''),
				'store_code'=>$request->store_code,
				'lines'=>$lines,
				'search'=>$request->search,
				'date_start'=> $date_start,
				'date_end'=> $date_end,
				'purchase_helper'=>new PurchaseOrderHelper(),
				'document_number'=>$request->document_number,
				'order_status'=> $this->order_status,
				'status_code'=>$request->status_code,
		]);
	}
}
