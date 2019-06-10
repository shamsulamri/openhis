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

			$value = $value->sum('inv_quantity');
			
			if (empty($value)) $value =0;

			return floatval($value);
	}

	public function getStockOnPurchase($product_code)
	{
			$sql = "
				select sum(line_quantity) as total_quantity
				from purchase_lines as a
				left join purchases as b on (b.purchase_id = a.purchase_id)
				left join inventories as c on (c.line_id = a.line_id)
				where document_code = 'purchase_order'
				and purchase_posted = 1
				and a.product_code = '".$product_code."'
				and inv_id is null
			";

			$data = DB::select($sql);

			return $data[0]->total_quantity;
	}

	public function getStockTransferIn($product_code, $store_code = null, $batch_number = null)
	{
			$sql = "
				select a.product_code, sum(a.inv_physical_quantity) as total_quantity
				from inventories as a
				left join inventory_movements as b on (b.move_id = a.move_id)
				where b.move_code = 'stock_issue'
				and b.tag_code = 'transfer'
				and move_posted = 1
				and a.product_code = '".$product_code."'
			";

			if (!empty($store_code)) {
					$sql = $sql . "
						and target_store = '".$store_code."'
					";
			}

			$sql = $sql."
				group by a.product_code, target_store
			";

			$data = DB::select($sql);

			if (sizeof($data)==0) {
					return 0;
			} else {
					return $data[0]->total_quantity - $this->getTransferReceive($product_code, $store_code);
			}
	}

	public function getStockTransferOut($product_code, $store_code = null, $batch_number = null)
	{
			$sql = "
				select a.product_code, sum(a.inv_physical_quantity) as total_quantity
				from inventories as a
				left join inventory_movements as b on (b.move_id = a.move_id)
				where b.move_code = 'stock_issue'
				and b.tag_code = 'transfer'
				and move_posted = 1
				and a.product_code = '".$product_code."'
			";

			if (!empty($store_code)) {
					$sql = $sql . "
						and a.store_code = '".$store_code."'
					";
			}

			$sql = $sql."
				group by a.product_code, target_store
			";

			$data = DB::select($sql);

			if (sizeof($data)==0) {
					return 0;
			} else {
					return $data[0]->total_quantity - $this->getTransferReceive($product_code, $store_code);
			}
	}

	public function getTransferReceive($product_code, $store_code = null, $batch_number = null)
	{
			$sql = "
				select sum(inv_quantity) as total_quantity
				from inventories as a
				left join inventory_movements as b on (b.move_id = a.move_reference)
				left join inventory_movements as c on (a.move_id = c.move_id)
				where move_reference is not null
				and b.move_code = 'stock_issue'
				and c.move_code = 'stock_receive'
				and b.target_store = c.store_code
				and a.product_code = '".$product_code."'
				group by a.product_code
			";

			$data = DB::select($sql);

			if (sizeof($data)==0) {
					return 0;
			} else {
					return $data[0]->total_quantity;

			}
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

			if ($value->count()>0) {
					if ($value->sum('inv_quantity')>0) {
							$value = $value->sum('inv_subtotal')/$value->sum('inv_quantity');
							$value = number_format($value,2);
					}
					return $value;
			} else {
					return 0;
			}
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

	public function getStockAvailable($product_code, $store_code=null, $batch_number = null)
	{
			$on_hand = $this->getStockOnHand($product_code, $store_code, $batch_number);
			$allocated = $this->getStockAllocated($product_code, $store_code, $batch_number);

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
			foreach ($product_uoms as $uom) {
					if ($uom->unit_code != 'unit') {
						$uom_list[$uom->unit_code] = $uom->unitMeasure->unit_name.' ('.$uom->uom_rate.')';
					}
			}

			return $uom_list;
	}

	public function getBatches($product_code, $order_id=null, $store_code=null)
	{
			/*
			$batches = Inventory::where('inventories.product_code', $product_code)
							->selectRaw('batch_id, inventories.product_code, batch_expiry_date, sum(inv_quantity) as sum_quantity, inv_batch_number')
							->leftjoin('inventory_batches as b', 'batch_number', '=', 'inv_batch_number')
							->leftjoin('ref_unit_measures as c', 'c.unit_code', '=', 'inventories.unit_code')
							->groupBy('batch_id')
							->groupBy('inventories.product_code')
							->groupBy('inv_batch_number')
							->groupBy('batch_expiry_date')
							->orderBy('batch_expiry_date')
							->havingRaw('sum_quantity>?',[0])
							->whereNotNull('inv_batch_number')
							->get();
			 */
			$batches = Inventory::where('inventories.product_code', $product_code)
							->select('batch_id', 'inventories.product_code', 'batch_expiry_date','inv_batch_number', DB::raw('sum(inv_quantity) as sum_quantity'))
							->leftjoin('inventory_batches as b', 'batch_number', '=', 'inv_batch_number')
							->leftjoin('ref_unit_measures as c', 'c.unit_code', '=', 'inventories.unit_code')
							->groupBy('inventories.product_code')
							->groupBy('batch_expiry_date')
							->groupBy('inv_batch_number')
							->orderBy('batch_expiry_date')
							->orderBy('inv_batch_number', 'desc')
							->havingRaw('sum(inv_quantity)>?',[0])
							->where('inv_posted', 1)
							->whereNotNull('inv_batch_number');

			/*
			if (!empty($order_id)) {
				$batches = Inventory::where('inventories.product_code', $product_code)
							->selectRaw('batch_id, inventories.product_code, batch_expiry_date, sum(inv_quantity) as sum_quantity, inv_batch_number')
							->leftjoin('inventory_batches as b', 'batch_number', '=', 'inv_batch_number')
							->leftjoin('ref_unit_measures as c', 'c.unit_code', '=', 'inventories.unit_code')
							->groupBy('batch_id')
							->groupBy('inventories.product_code')
							->groupBy('inv_batch_number')
							->groupBy('batch_expiry_date')
							->orderBy('batch_expiry_date')
							->where('inventories.order_id', $order_id)
							->where('inv_posted', 1)
							->where('move_code', 'sale');
			}
			 */

			if ($store_code) {
				$batches = $batches->where('store_code', $store_code);
			}

			$batches = $batches->get();

			foreach ($batches as $batch) {
				Log::info($batch->sum_quantity);
			}
			return $batches;
	}

	public function updateStockOnHand($product_code)
	{
			$product = Product::find($product_code);
			if ($product->product_stocked == 1) {
				$product->product_on_hand = $this->getStockOnHand($product_code); 
				$product->save();
			}
	}

}


