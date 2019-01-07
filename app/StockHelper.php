<?php

namespace App;
use DB;
use Carbon\Carbon;
use App\Inventory;
use Log;
use Auth;
use App\ProductUom;
use App\UnitMeasure;
use App\InventoryBatch;

class StockHelper 
{
	public function getStockOnHand($product_code, $store_code = null, $batch_number = null)
	{
			$value = Inventory::where('product_code',$product_code)
						->where('inv_posted', 1);


			if (!empty($store_code)) {
					$value = $value->where('store_code', $store_code);
			}

			if (!empty($batch_number)) {
					$value = $value->where('inv_batch_number', $batch_number);
			}

			Log::info($product_code);
			Log::info($store_code);
			$value = $value->sum('inv_quantity');
			
			if (empty($value)) $value =0;

			return floatval($value);
	}

	public function getStockOnPurchase($product_code, $store_code = null, $batch_number = null)
	{
			return 99;
	}

	public function getStockOnIssue($product_code, $store_code = null, $batch_number = null)
	{
			return 99;
	}

	public function getStockAverageCost($product_code, $store_code = null, $batch_number = null)
	{
			$value = Inventory::where('product_code',$product_code)
						->where('inv_posted', 1);

			if (!empty($store_code)) {
					$value = $value->where('store_code', $store_code);
			}

			if (!empty($batch_number)) {
					$value = $value->where('inv_batch_number', $batch_number);
			}

			$value = $value->sum('inv_subtotal')/$value->sum('inv_quantity')?:0;
			$value = number_format($value,2);
			return $value;
	}

	public function getStockTotalCost($product_code, $store_code = null, $batch_number = null)
	{
			$value = Inventory::where('product_code',$product_code)
						->where('inv_posted', 1);

			if (!empty($store_code)) {
					$value = $value->where('store_code', $store_code);
			}

			if (!empty($batch_number)) {
					$value = $value->where('inv_batch_number', $batch_number);
			}

			$value = $value->sum('inv_subtotal')?:0;
			$value = number_format($value,2);
			return $value;
	}

	public function getStockAllocated($product_code, $store_code=null,$encounter_id=null) 
	{
			$allocated = Order::where('product_code','=',$product_code)
					->leftjoin('order_cancellations as a', 'a.order_id', '=', 'orders.order_id')
					->leftjoin('discharges as b', 'b.encounter_id', '=', 'orders.encounter_id')
					->where('order_completed','=', 0)
					->whereNull('discharge_id')
					->whereNull('cancel_id');

			if (!empty($encounter_id)) {
					$allocated = $allocated->where('orders.encounter_id','<>', $encounter_id);
			}

			if (!empty($store_code)) {
					$allocated = $allocated->where('orders.store_code', $store_code);
			}

			$allocated = $allocated->sum('order_quantity_request');

			return floatval($allocated);

	}

	public function getStockCompleted($product_code, $store_code=null,$encounter_id=null) 
	{
			$allocated = Order::where('product_code','=',$product_code)
					->where('order_completed','=', 1);

			if (!empty($encounter_id)) {
					$allocated = $allocated->where('encounter_id','<>', $encounter_id);
			}

			if (!empty($store_code)) {
					$allocated = $allocated->where('orders.store_code', $store_code);
			}

			$allocated = $allocated->sum('order_quantity_request');

			return floatval($allocated);

	}

	public function getStockAvailable($product_code, $store_code=null)
	{
			$on_hand = $this->getStockOnHand($product_code, $store_code);
			$allocated = $this->getStockAllocated($product_code, $store_code);

			return $on_hand - $allocated;
	}

	public function getUOM($product_code)
	{
			$uoms = ProductUOM::select('b.unit_code', 'b.unit_name')
						->leftjoin('ref_unit_measures as b', 'b.unit_code', '=', 'product_uoms.unit_code')
						->where('product_code', $product_code)
						->orderBy('unit_name')
						->lists('unit_name', 'unit_code');


			$product = Product::find($product_code);
			$product_uoms =  $product->productUnitMeasures();

			$uom_list = [];
			$uom_list['unit'] = 'Unit';
			foreach ($product_uoms as $uom) {
					if ($uom->unit_code != 'unit') {
						$uom_list[$uom->unit_code] = $uom->unitMeasure->unit_name;
					}
			}

			return $uom_list;
	}

	public function getBatches($product_code)
	{
			//$batches = InventoryBatch::where('product_code', $product_code)->get();

			$batches = Inventory::where('inventories.product_code', $product_code)
							->selectRaw('sum(inv_quantity) as sum_quantity, inv_batch_number, inventories.product_code, unit_code')
							->leftjoin('inventory_batches as b', 'batch_number', '=', 'inv_batch_number')
							->groupBy('inv_batch_number')
							->groupBy('unit_code')
							->orderBy('batch_expiry_date')
							->where('inventories.unit_code', 'unit')
							->whereNotNull('inv_batch_number')
							->get();

			Log::info($batches);
			return $batches;
	}

}


