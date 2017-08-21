<?php

namespace App;
use DB;
use Carbon\Carbon;
use App\StockStore;
use App\PurchaseOrderLine;
use Log;

class StockHelper 
{
	public function getStockCountByStore($product_code, $store_code) 
	{
			$value = StockStore::where('product_code',$product_code);
				
			if (!empty($store_code)) {
					$value = $value->where('store_code', $store_code);
			}

			$value = $value->sum('stock_quantity');
			
			return floatval($value);

	}

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

	public function updateAllStockOnHand($product_code)
	{
			StockStore::where('product_code','=', $product_code)->delete();
			$stores = Store::all();
			$total=0;
			foreach ($stores as $store) {
					$total += $this->stockOnHand($product_code, $store->store_code);
			}
					
			$product = Product::find($product_code);
			$product->product_on_hand = $total;
			Log::info($total);
			Log::info($product->product_on_hand);
			$product->save();		

			return $total;
	}

	public function stockReceive($id, $quantity, $store_code, $batch_number, $delivery_number, $invoice_number, $expiry_date)
	{
			Log::info($expiry_date);
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
			Log::info($stock);

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
			$sum = Stock::where('line_id',$line_id)->sum('stock_quantity');

			if (empty($sum)) $sum='-';
			return $sum;

	}
}

