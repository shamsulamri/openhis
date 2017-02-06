<?php

namespace App;
use DB;
use Carbon\Carbon;
use App\StockStore;
use Log;

class StockHelper 
{
	public function getStockCount($product_code, $store_code)
   	{

			return $this->storeOnHand($product_code, $store_code);
	}

	public function getStockCountByStore($product_code, $store_code) 
	{
			$value = StockStore::where('product_code',$product_code);
				
			if (!empty($store_code)) {
					$value = $value->where('store_code', $store_code);
			}

			$value = $value->sum('stock_quantity');
			
			return floatval($value);

	}

	public function storeOnHand($product_code, $store_code) 
	{
			$stock_take = Stock::select('stock_datetime', 'stock_quantity')
							->where('move_code','=','take')
							->where('product_code','=',$product_code)
							->where('store_code','=',$store_code)
							->orderBy('stock_datetime', 'desc')
							->first();

			$stock_on_hand=0;
			if (!empty($stock_take)) {
					$stock_value = $stock_take->stock_quantity; 

					$take_date = Carbon::createFromFormat('d/m/Y H:i',$stock_take->stock_datetime);

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

			return $stock_on_hand;
	}

}

