<?php

namespace App;
use DB;
use Carbon\Carbon;
use App\StockStore;
use App\PurchaseOrderLine;
use Log;
use Auth;
use App\StockInputBatch;

class StockHelper 
{
	public function getStockCountByStore($product_code, $store_code=null) 
	{
			$value = StockStore::where('product_code',$product_code);
				
			if (!empty($store_code)) {
					$value = $value->where('store_code', $store_code);
			}

			$value = $value->sum('stock_quantity');
			
			if (empty($value)) $value =0;

			return floatval($value);
	}

	public function getStockAllocatedByStore($product_code, $store_code=null,$encounter_id=null) 
	{
			$allocated = Order::where('product_code','=',$product_code)
					->leftjoin('order_cancellations as a', 'a.order_id', '=', 'orders.order_id')
					->where('order_completed','=', 0)
					->whereNull('cancel_id');

			if (!empty($encounter_id)) {
					$allocated = $allocated->where('encounter_id','<>', $encounter_id);
			}

			if (!empty($store_code)) {
					$allocated = $allocated->where('orders.store_code', $store_code);
			}

			$allocated = $allocated->sum('order_quantity_request');

			return floatval($allocated);

	}

	public function getStockAvailable($product_code, $store_code) 
	{
			$value = $this->getStockCountByStore($product_code, $store_code)-$this->getStockAllocatedByStore($product_code, $store_code);
			if ($value<0) $value=0;

			return $value;
	}

	public function onPurchase($product_code)
	{
			$quantity = PurchaseOrderLine::where('purchase_order_lines.product_code',$product_code)
						->leftjoin('stock_input_lines as b','b.po_line_id', '=', 'purchase_order_lines.line_id')
						->leftjoin('stock_inputs as c', 'c.input_id', '=', 'b.input_id')
						->where('input_close','=', 0);

			return $quantity->sum('line_quantity_ordered');
	}

	public function onTransfer($product_code, $store_code)
	{
			$quantity = StockInputLine::where('product_code','=', $product_code)
							->leftjoin('stock_inputs as b', 'b.input_id','=', 'stock_input_lines.input_id')
							->where('input_close','=', 0)
							->where('store_code', $store_code)
							->where('move_code','=','transfer');

			return $quantity->sum('line_quantity');
	}

	public function inTransfer($product_code, $store_code)
	{
			$quantity = StockInputLine::where('product_code','=', $product_code)
							->leftjoin('stock_inputs as b', 'b.input_id','=', 'stock_input_lines.input_id')
							->where('input_close','=', 0)
							->where('store_code_transfer', $store_code)
							->where('move_code','=','transfer');

			return $quantity->sum('line_quantity');
	}

	public function stockOnHand($product_code, $store_code) 
	{
			$stock_on_hand = Stock::where('product_code','=',$product_code)
							->where('store_code','=',$store_code)
							->sum('stock_quantity');

			if ($stock_on_hand>0) {
					$stock_store = StockStore::where('product_code','=', $product_code)
									->where('store_code','=', $store_code)
									->first();
					if (empty($stock_store)) {
							$stock_store = new StockStore();
					}

					$stock_store->product_code = $product_code;
					$stock_store->store_code = $store_code;
					$stock_store->stock_quantity = $stock_on_hand;
					$stock_store->save();
			}
	}

	/**
	public function stockOnHand($product_code, $store_code) 
	{

			$stock_take = Stock::select('stock_datetime', 'stock_quantity')
							->where('move_code','=','take')
							->where('product_code','=',$product_code)
							->where('store_code','=',$store_code)
							->orderBy('stock_datetime', 'desc')
							->orderBy('stock_id', 'desc')
							->first();

			$stock_on_hand=0;

			if (!empty($stock_take)) {
					$stock_value = $stock_take->stock_quantity; 

					$take_date = $stock_take->stock_datetime; 

					$stocks = Stock::where('stock_datetime','>=',$take_date)
									->where('product_code','=',$product_code)
									->where('store_code','=',$store_code)
									->where('move_code','<>', 'take')
									->sum('stock_quantity');

					$used = Order::where('product_code','=', $product_code)
								->where('created_at','>', $take_date)
								->where('order_completed','=',1)
								->sum('order_quantity_supply');

					$stock_on_hand = $stock_value + $stocks - $used;
			} else {
					$stocks = Stock::where('product_code','=',$product_code)
									->where('store_code','=',$store_code)
									->sum('stock_quantity');

					$used = Order::where('product_code','=', $product_code)
								->where('order_completed','=',1)
								->where('store_code', '=', $store_code)
								->sum('order_quantity_supply');

					$stock_on_hand=$stocks - $used;
			}

			if ($stock_on_hand>0) {
					$stock_store = new StockStore();
					$stock_store->product_code = $product_code;
					$stock_store->store_code = $store_code;
					$stock_store->stock_quantity = $stock_on_hand;
					$stock_store->save();
			}


			Log::info($store_code.':'.$stock_on_hand);
			return $stock_on_hand;
	}
	**/

	public function updateAllStockOnHand($product_code)
	{
			StockStore::where('product_code','=', $product_code)->delete();
			$stores = Store::all();
			$total=0;
			foreach ($stores as $store) {
					$total += $this->stockOnHand($product_code, $store->store_code);
			}
					
			$total = Stock::where('product_code','=',$product_code)
							->sum('stock_quantity');

			$product = Product::find($product_code);
			$product->product_on_hand = $total;
			$product->product_average_cost = $this->updateAverageCost($product_code);

			$product->save();		

			return $total;
	}

	public function updateAverageCost($product_code) 
	{
			$average_cost = Stock::select(DB::raw('sum(stock_value)/sum(stock_quantity) as average_cost'))
							->where('product_code','=', $product_code)
							->where('move_code','<>','sale')
							->get();

			return $average_cost[0]->average_cost;
	}

	public function stockReceive($id, $quantity, $store_code, $batch_number, $delivery_number, $invoice_number, $expiry_date)
	{
			$item = PurchaseOrderLine::find($id);

			$product = Product::find($item->product_code);
			$source = Product::find($item->product_code);
			if (!empty($product->product_conversion_code)) {
					$conversion_code = $product->product_conversion_code;
					$product = Product::find($conversion_code);
			}
			$stock = new Stock();
			$stock->line_id = $item->line_id;
			$stock->store_code = $store_code;
			$stock->move_code = 'receive';
			$stock->product_code = $product->product_code;
			$stock->stock_datetime = DojoUtility::now();
			$stock->stock_quantity = $quantity;
			$stock->batch_number = $batch_number;
			$stock->delivery_number = $delivery_number;
			$stock->invoice_number = $invoice_number;
			$stock->expiry_date = $expiry_date;
			if ($source->product_conversion_unit>0) {
				$stock->stock_quantity = $quantity*$source->product_conversion_unit;
			}
			$stock->stock_description = "Purchase id: ".$item->purchase_id;
			$stock->save();

			if ($product->product_track_batch==1) {
					$batch = new StockBatch();
					$batch->stock_id = $stock->stock_id;
					$batch->product_code = $product->product_code;
					$batch->batch_number = $batch_number;
					$batch->expiry_date = $stock->expiry_date;
					$batch->batch_quantity = $quantity;
					$batch->save();
			}

			$this->updateAllStockOnHand($product->product_code);
	}

	public function stockReceiveSum($line_id)
	{
			$sum = StockInputLine::where('po_line_id',$line_id)->sum('line_quantity');

			if (empty($sum)) $sum='-';
			return $sum;

	}

	public function getBatches($product_code, $store_code)
	{
			$batches = StockBatch::selectRaw('sum(batch_quantity) as batch_quantity, expiry_date,product_code, batch_number')
						->where('product_code','=', $product_code)
						->where('store_code','=', $store_code)
						->having('batch_quantity','>',0)
						->groupBy('batch_number')
						->orderBy('expiry_date')
						->get();

			return $batches;
	}

	public function getFirstBatch($product_code, $store_code)
	{
			$batch = StockBatch::selectRaw('sum(batch_quantity) as batch_quantity, expiry_date,product_code, batch_number')
						->where('product_code','=', $product_code)
						->where('store_code','=', $store_code)
						->having('batch_quantity','>',0)
						->groupBy('batch_number')
						->orderBy('expiry_date')
						->first();

			return $batch;
	}
	public function getBatchTotal($product_code, $store_code, $batch_number)
	{
			$total = StockBatch::where('product_code','=', $product_code)
						->where('store_code','=', $store_code)
						->where('batch_number','=', $batch_number)
						->sum('batch_quantity');

			return $total;
	}

	public function moveStock($stock) 
	{
			if ($stock->move_code=='take') {
					Stock::where('product_code','=', $stock->product_code)
							->where('store_code', '=', $stock->store_code)
							->delete();
			}

			$stock->username = Auth::user()->username;

			switch($stock->move_code) {
					case "loan_in":
							$stock->stock_quantity = abs($stock->stock_quantity);
							$stock->save();
							break;
					case "loan_out":
							$stock->stock_quantity = -($stock->stock_quantity);
							$stock->stock_value = -($stock->stock_value);
							$stock->save();
							break;
					case "receive":
							$stock->stock_quantity = abs($stock->stock_quantity);
							$stock->save();
							break;
					case "dispose":
							$stock->stock_quantity = -($stock->stock_quantity);
							$stock->stock_value = -($stock->stock_value);
							$stock->save();
							break;
					case "return":
							$stock->stock_quantity = -($stock->stock_quantity);
							$stock->stock_value = -($stock->stock_value);
							$stock->save();
							break;
					case "transfer":
							$store = Store::find($stock->store_code_transfer);
							$stock->stock_quantity = ($stock->stock_quantity)*-1;
							$stock->stock_description = "Transfer out to ".$store->store_name;
							$stock->stock_value = -($stock->stock_value);
							$stock->save();

							$store = Store::find($stock->store_code);
							$transfer = new Stock();
							$transfer->product_code = $stock->product_code;
							$transfer->username = $stock->username;
							$transfer->move_code = $stock->move_code;
							$transfer->store_code_transfer = $stock->store_code;
							$transfer->store_code = $stock->store_code_transfer;
							$transfer->stock_quantity = abs($stock->stock_quantity);
							$transfer->stock_value = abs($stock->stock_value);
							$transfer->stock_tag = $stock->stock_id;
							$transfer->stock_description = "Transfer in from ".$store->store_name;
							$transfer->save();

							break;
					default:
							// Adjustment
							if ($stock->stock_quantity<0) $stock->stock_value = -($stock->stock_value);
							$stock->save();

			}
			
			$this->updateAllStockOnHand($stock->product_code);

			return $stock;
	} 

	public function moveStockBatch($request, $stock)
	{
			if ($stock->move_code=='take') {
					StockBatch::where('product_code','=', $stock->product_code)->delete();
			}

			$batches = $this->getBatches($stock->product_code);

			foreach($batches as $batch) {
					$batch_quantity = $request[$stock->product_code.'_'.$batch->batch_number];
					$batch_quantity = abs($batch_quantity);

					if ($stock->stock_quantity<0) {
							$batch_quantity = -($batch_quantity);
					}

					if (!empty($batch_quantity)) {
							$new_batch = new StockBatch();
							$new_batch->stock_id = $stock->stock_id;
							$new_batch->product_code = $batch->product_code;
							$new_batch->batch_number = $batch->batch_number;
							$new_batch->batch_quantity = $batch_quantity;
							$new_batch->expiry_date = $batch->expiry_date;
							$new_batch->save();
					}
			}

			if (!empty($request->batch_number_new)) {
					$new_batch = new StockBatch();
					$new_batch->stock_id = $stock->stock_id;
					$new_batch->product_code = $stock->product_code;
					$new_batch->batch_number = $request->batch_number_new;
					$new_batch->batch_quantity = $request->batch_quantity_new;
					$new_batch->expiry_date = DojoUtility::dateWriteFormat($request->batch_expiry_date_new);
					$new_batch->save();
			}
	}

	public function getStockInputBatchCount($line_id) 
	{
			$count = StockInputBatch::where('line_id',$line_id)
						->sum('batch_quantity');
			
			if (empty($count)) $count=0;
			return $count;
	}

	public function updateStockBatch($order) 
	{
		$stock = new Stock();
		$stock->order_id = $order->order_id;
		$stock->product_code = $order->product_code;
		$stock->stock_quantity = -($order->order_quantity_supply);
		$stock->store_code = $order->store_code;
		$stock->stock_value = -($order->product->product_average_cost*$order->order_quantity_supply);
		$stock->move_code = 'sale';
		$stock->save();

		$this->updateAllStockOnHand($order->product_code);

		if ($stock->product->product_track_batch==1) {

				$batch = $this->getFirstBatch($stock->product_code, $stock->store_code);

				if ($batch) {
						$stock_batch = new StockBatch();
						$stock_batch->stock_id = $stock->stock_id;
						$stock_batch->store_code = $stock->store_code;
						$stock_batch->product_code = $stock->product_code;
						$stock_batch->batch_number = $batch->batch_number;
						$stock_batch->expiry_date = $batch->expiry_date;
						$stock_batch->batch_quantity = $stock->stock_quantity;
						$stock_batch->save();
				}


		} 							
	}
}

