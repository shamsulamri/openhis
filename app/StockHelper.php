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

			/*
			$last_adjustment = Inventory::where('product_code',$product_code)
						->where('move_code', 'stock_adjust')
						->orderBy('inv_id', 'desc')
						->where('inv_posted', 1);

			if (!empty($batch_number)) {
				$last_adjustment = $last_adjustment->where('inv_batch_number', $batch_number);
			}

			if (!empty($store_code)) {
					$last_adjustment = $last_adjustment->where('store_code', $store_code);
			}

			$last_adjustment = $last_adjustment->first();

			$value = null;
			if ($last_adjustment) {
					$value = Inventory::where('product_code',$product_code)
						->where('inv_posted', 1)
						->where('inv_id', '>=', $last_adjustment->inv_id);
			} else {
					$value = Inventory::where('product_code',$product_code)
						->where('inv_posted', 1);
			}
			*/
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

	public function getStockAverageCost($product_code, $date=null)
	{
			/*
			$sql = "
				select sum(inv_subtotal)/sum(inv_quantity) as average_cost
				from inventories as a
				where move_code = 'goods_receive'
				and product_code = '".$product_code."'
				and inv_posted=1";
			 */

			$sql = "
				select sum(line_subtotal_tax)/sum(uom_rate*line_quantity) as average_cost
				from purchase_lines a
				left join purchases as b on (b.purchase_id = a.purchase_id)
				where product_code = '".$product_code."'
				and document_code = 'goods_receive'
				and line_posted = 1
			";


			if ($date) {
				$sql = $sql." and a.created_at<'".$date."'";
				Log::info($sql);
			}

			$data = DB::select($sql);

			if (sizeof($data)==0) {
					return 0;
			} else {
					return $data[0]->average_cost;

			}

	}

	public function getStockAverageCost2($product_code, $store_code = null, $batch_number = null)
	{
			$average_cost=0;
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
							if ($value->sum('inv_subtotal')>0) {
									$value = $value->sum('inv_subtotal')/$value->sum('inv_quantity');
									$average_cost = number_format($value,2);
							}
					} else {
							$average_cost = 0;
					}
			} else {
					$average_cost = 0;
			}

			return $average_cost;
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
					Log::info("X");
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
			Log::info($on_hand.'---'.$allocated);

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
					//if ($uom->unit_code != 'unit') {
						$uom_list[$uom->unit_code] = $uom->unitMeasure->unit_name.' ('.$uom->uom_rate.')';
					//}
			}

			return $uom_list;
	}

	public function getBatches($product_code, $order_id=null, $store_code=null)
	{
			/*
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
			 */

			$batches = Inventory::where('inventories.product_code', $product_code)
							->select('batch_id', 'inventories.product_code', 'batch_expiry_date','inv_batch_number', DB::raw('sum(inv_quantity) as sum_quantity'))
							->leftjoin('inventory_batches as b', function($join)
							{
								$join->on('b.batch_number', '=', 'inventories.inv_batch_number');
								$join->on('b.product_code', '=', 'inventories.product_code');
							})
							->leftjoin('ref_unit_measures as c', 'c.unit_code', '=', 'inventories.unit_code')
							->groupBy('inventories.product_code')
							->groupBy('batch_expiry_date')
							->groupBy('inv_batch_number')
							->orderBy('batch_expiry_date')
							->orderBy('inv_batch_number', 'desc')
							->havingRaw('sum(inv_quantity)>?',[0])
							->whereNotNull('batch_id')
							->where('inv_posted', 1)
							->whereNull('b.deleted_at')
							->whereNotNull('inv_batch_number');

			if ($order_id) {

					$order = Order::find($order_id);

					if ($order->product->product_local_store==1) {
							$store_code = $order->store_code;
					}
					$batches = $batches->where('inventories.inv_datetime', '<', $order->created_at);
					Log::info($order->created_at);
			}

			if ($store_code) {
					$batches = $batches->where('store_code', $store_code);
			}

			$batches = $batches->get();

			return $batches;
	}

	public function updateStockOnHand($product_code)
	{
			$product = Product::find($product_code);
			if ($product->product_stocked == 1) {
				$product->product_on_hand = $this->getStockOnHand($product_code); 
				if ($product->product_on_hand>0) {
					//$product->status_code = 'active';
				}
				$product->save();
			}
	}

	public function outstandingQuantity($line_id, $product_code)
	{
			$item = PurchaseLine::find($line_id);

			$outstanding_quantity = Inventory::where('line_id', $line_id)
					->where('product_code', $product_code)
					->sum('inv_physical_quantity');

			if (empty($outstanding_quantity)) {
					$outstanding_quantity = $item->line_quantity;
			} else {
					$outstanding_quantity = $item->line_quantity-$outstanding_quantity;
			}

			return $outstanding_quantity;
	}

}


