<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use App\OrderMultiple;
use App\EncounterHelper;
use App\Order;
use App\OrderPost;
use App\Queue;
use Session;
use Auth;
use Log;
use App\Ward;
use App\QueueLocation;
use App\StockHelper;
use App\Http\Controllers\ProductController;
use Config;

class OrderHelper 
{
	public function getMultipleOrder($order_id) 
	{
			$orders = OrderMultiple::where('order_id', '=',$order_id)->get();
			return $orders;
	}

	public static function getOrder($order_id) 
	{
			$order = Order::find($order_id);
			return $order;
	}

	public function getPrescription($order_id)
	{
			$drug = OrderDrug::where('order_id', $order_id)->first();

			$value = "";
			if ($drug) {
					if (!empty($drug->dosage_code)) $value = $drug->drug_dosage." ".$drug->dosage->dosage_name;
					if (!empty($drug->route_code)) $value = $value.", ".$drug->route->route_name.", ".$drug->frequency_code;
					if (!empty($drug->drug_duration)) $value = $value.", ".$drug->drug_duration;
					if (!empty($drug->period_code)) $value = $value." ".$drug->period_code;
					$value = trim($value, ",");
			}

			return $value;
	}

	/**
	public static function getStoreAffected2($product)
	{
			$admission = EncounterHelper::getCurrentAdmission(Session::get('encounter_id'));
			$encounter = Encounter::find(Session::get('encounter_id'));

			$store_code = null;
			if ($admission) {
				$store_code = $admission->bed->ward->store_code;
			} else {
				$location = Queue::where('encounter_id', '=', $encounter->encounter_id)->first()->location;
				if ($location) $store_code = $location->store_code;
			}

			if ($product->product_stocked==1) {
				if ($product->product_local_store==0) {
					$route = OrderRoute::where('encounter_code', $encounter->encounter_code)
							->where('category_code', $product->category_code)
							->first();

					if ($admission) {
						$route_ward = OrderRoute::where('encounter_code', $encounter->encounter_code)
							->where('category_code', $product->category_code)
							->where('ward_code', $admission->bed->ward->ward_code)
							->first();
						if ($route_ward) {
							$route = $route_ward;
						}
					}

					if ($route) {
						$store_code = $route->store_code;
					}
				}
			} else {
				$store_code = null;
			}

			return $store_code;
	}
	**/

	public static function getTargetLocation($product)
	{
			$admission = EncounterHelper::getCurrentAdmission(Session::get('encounter_id'));
			$encounter = Encounter::find(Session::get('encounter_id'));

			/*** Set Route ***/
			$route = null;
			$target_location = null;
			if ($admission) {
						$route = OrderRoute::where('encounter_code', $encounter->encounter_code)
								->where('category_code', $product->category_code)
								->where('ward_code', $admission->bed->ward->ward_code)
								->first();
						if (empty($route)) {
								$route = OrderRoute::where('encounter_code', $encounter->encounter_code)
										->where('category_code', $product->category_code)
										->first();
						}
			} else {
						$route = OrderRoute::where('encounter_code', $encounter->encounter_code)
								->where('category_code', $product->category_code)
								->first();
			}

			if ($route) {
					$target_location = $route->location_code;
			}

			return $target_location;
	}

	public static function getTargetStore($product)
	{
			$store_code = null;
			if ($product->product_stocked==1) {
					$encounter = Encounter::find(Session::get('encounter_id'));
					$admission = EncounterHelper::getCurrentAdmission(Session::get('encounter_id'));
					$location_code = (new self)->getTargetLocation($product);

					if (!empty($location_code)) {
								$store_code = QueueLocation::find($location_code)->store_code;
					} else {
								$store_code = (new self)->getLocalStore($encounter, $admission);
					}

					if ($product->product_local_store==1) {
								$store_code = (new self)->getLocalStore($encounter, $admission);
					}
			}

			return $store_code;
	}

	public static function getLocalStore($encounter, $admission)
	{
				$store_code = null;
				if ($admission) {
						$store_code = $admission->bed->ward->store_code;
				} else {
						$location = Queue::where('encounter_id', '=', $encounter->encounter_id)->first()->location;
						if ($location) $store_code = $location->store_code;
				}
				return $store_code;
	}

	/**
	public static function getTargetLocation2($product)
	{
			$encounter = Encounter::find(Session::get('encounter_id'));

			$route = OrderRoute::where('encounter_code', $encounter->encounter_code)
					->where('category_code', $product->category_code)
					->first();

			$target=$product->location_code;
			if ($route) {
					$target = $route->location_code;
			}

			return $target;
	}
	**/

	public static function orderItem($product, $ward_code)
	{
			$admission = EncounterHelper::getCurrentAdmission(Session::get('encounter_id'));

			$order = new Order();
			$order->consultation_id = Session::get('consultation_id');
			$order->encounter_id = Session::get('encounter_id');
			$order->user_id = Auth::user()->id;
			$order->unit_code = $product->uomDefaultPrice()?$product->uomDefaultPrice()->unit_code:'unit';
			$order->order_unit_price = $product->uomDefaultPrice()?$product->uomDefaultPrice()->uom_price:'unit';
			$order->product_code = $product->product_code;
			$order->order_quantity_request = 1;

			if ($admission) {
					$order->admission_id = $admission->admission_id;
					$order->ward_code = $admission->bed->ward_code;
			}

			$location_code = (new self)->getTargetLocation($product);
			$order->location_code = $location_code;
			$order->store_code = (new self)->getTargetStore($product);

			$order->save();

			if ($product->order_form==2) {
					$order_drug = new OrderDrug();
					$order_drug->order_id = $order->order_id;
					$drug_prescription = DrugPrescription::where('drug_code','=',$product->product_code)->first();
					if (!empty($drug_prescription)) {
							$order_drug->drug_strength = $drug_prescription->drug_strength;
							$order_drug->unit_code = $drug_prescription->unit_code;
							$order_drug->drug_dosage = $drug_prescription->drug_dosage;
							$order_drug->dosage_code = $drug_prescription->dosage_code;
							$order_drug->route_code = $drug_prescription->route_code;
							//$order_drug->frequency_code = $drug_prescription->frequency_code;
							$order_drug->drug_duration = $drug_prescription->drug_duration;
							$order_drug->period_code = $drug_prescription->period_code;
							$order_drug->drug_prn = $drug_prescription->drug_prn;
							$order_drug->drug_meal = $drug_prescription->drug_meal;
							$order->order_quantity_request = $drug_prescription->drug_total_unit;
							$order->order_quantity_supply = $drug_prescription->drug_total_unit;
							$order->order_description = $drug_prescription->drug_description;

							if ($order->order_quantity_request==0) {
									$order->order_quantity_request = 1;
									$order->order_quantity_supply = 1;
							}
							$order->save();
					}
					$order_drug->save();
					//OrderHelper::createDrugServings($order_drug);
					//$order->save();
			}

			if ($product->order_form==3) {
					$order_investigation = new OrderInvestigation();
					$order_investigation->order_id = $order->order_id;
					$order_investigation->investigation_date = date('d/m/Y');
					$order_investigation->save();
			}

			return $order->order_id;

	}	

	public static function createDrugServings($order_drug) 
	{
		$unit_of_dose = Config::get('host.unit_of_dose');
		if ($unit_of_dose==1) {
				if ($order_drug->order->consultation->encounter->encounter_code == 'inpatient') {
						if (!empty($order_drug->period->period_mins) && !empty($order_drug->frequency->frequency_value)) {
							$multi = OrderMultiple::where('order_id','=', $order_drug->order_id)->delete();
							$frequencies = $order_drug->frequency->frequency_value*$order_drug->drug_duration*($order_drug->period->period_mins/1440);
							
							$is_unit_of_dose = Config::get('host.unit_of_dose');
							if ($frequencies>0 & $is_unit_of_dose==1) {
									OrderMultiple::where('order_id',$order_drug->order_id)->delete();
									for ($i=0; $i<$frequencies; $i++) {
											$multi = new OrderMultiple();
											$multi->order_id = $order_drug->order_id;
											$multi->save();
									}
									$order = Order::find($order_drug->order_id);
									$order->order_multiple=1;
									$order->save();
							} else {
									$order = Order::find($order_drug->order_id);
									$order->order_multiple=0;
									$order->save();
							}
						}
				}
		}
	}

	public static function createInvestigationOrders($order_investigation) 
	{
		if (!empty($order_investigation->period->period_mins) && !empty($order_investigation->frequency->frequency_mins)) {
			$multi = OrderMultiple::where('order_id','=', $order_investigation->order_id)->delete();
			$frequencies = ($order_investigation->investigation_duration*$order_investigation->period->period_mins)/$order_investigation->frequency->frequency_mins;
			
			if ($frequencies>0) {
					OrderMultiple::where('order_id',$order_investigation->order_id)->delete();
					for ($i=0; $i<$frequencies; $i++) {
							$multi = new OrderMultiple();
							$multi->order_id = $order_investigation->order_id;
							$multi->save();
					}
					$order = Order::find($order_investigation->order_id);
					$order->order_multiple=1;
					$order->save();
			} else {
					$order = Order::find($order_investigation->order_id);
					$order->order_multiple=0;
					$order->save();
			}
		}
	}

		public static function hasOpenOrders($user_id)
		{
				$orders = Order::where('orders.user_id', $user_id)
							->distinct('orders.encounter_id')
							->leftjoin('discharges as b', 'orders.encounter_id','=', 'b.encounter_id')
							->where('post_id',0)
							->whereNull('discharge_id')
							->groupBy('orders.encounter_id')
							->get();

				if ($orders) {
					return $orders;
				} else {
					return null;
				}
		}
		
		public static function drugDescription($id, $prefix) 
		{
			$description = "";
				

			$order_drug = OrderDrug::where('order_id', $id)->first();

			if ($order_drug) {
					if ($order_drug->drug_strength>0) {
						$description .= $order_drug->drug_strength." ".$order_drug->unit->unit_name.", ";
					}

					if ($order_drug->drug_dosage>0) {
						$description .= $order_drug->drug_dosage." ".$order_drug->dosage->dosage_name.", ";
					}

					if ($order_drug->route) {
						$description .= $order_drug->route->route_name .", ";
					}

					if ($order_drug->frequency) {
						$description .= $order_drug->frequency->frequency_name.", ";
					}

					if ($order_drug->drug_duration>0) {
						$description .= $order_drug->drug_duration." ".strtolower($order_drug->period->period_name);
					}

					$description = trim($description);
					if (substr($description,strlen($description)-1,1)==',') {
						$description = substr($description,0,strlen($description)-1);
					}

					if (empty($description)) return "";

					return $prefix.$description;
			}
		}

	public static function dropCharge($consultation_id) 
	{
		$orders = Order::where('consultation_id',$consultation_id)
					->leftJoin('products as c', 'c.product_code', '=', 'orders.product_code')
					->where('product_duration_use', '=',0)
					->where('post_id', 0)
					->get();

		$stock_helper = new StockHelper();

		foreach($orders as $order) {

			$drop_now = false;

			//if ($order->product->location_code=='none' or $order->product->location == null) $drop_now = true;
			if ($order->product_drop_charge==1) $drop_now = true;

				/**
				if ($order->product->product_stocked==1 & $order->product_drop_charge==1) {
					if ($stock_helper->getStockCountByStore($order->product_code,$order->store_code)>0) {
						$ward = Ward::where('ward_code', $ward_code)->first();
						$order->store_code = $ward->store_code;
					}
				}
				**/
					
			if ($order->order_is_discharge==1) {
					$route = OrderRoute::where('encounter_code', $order->consultation->encounter->encounter_code)
							->where('category_code', $order->product->category_code)
							->first();
					if ($route) {
							$drop_now = false;
					}
			}

			if ($drop_now) {
				if (!$order->orderCancel) {
						$order->order_completed=1;
						$order->completed_at = DojoUtility::dateTimeWriteFormat(DojoUtility::now());
						$order->save();
				}

				if ($order->product->product_stocked==1) {

						$batches = $stock_helper->getBatches($order->product_code, null, $order->store_code)?:null;

						if ($batches->count()>0) {
							$quantity_request = $order->order_quantity_request;
							foreach ($batches as $batch) {
									$supply = 0;
									if ($batch->sum_quantity<$quantity_request) {
										$supply = $batch->sum_quantity;
										$quantity_request -= $batch->sum_quantity;
									} else {
										$supply = $quantity_request;
										$quantity_request = $quantity_request-$supply;
									}
									$order->order_quantity_supply = $supply;
									self::addToInventory($order, $batch->inv_batch_number);
							}
						} else {
							self::addToInventory($order);
						}

				}
			}
		}
	}

	public static function addToInventory($order, $batch_number = null) 
	{
		$total_supply = $order->order_quantity_supply;
		$inventory = new Inventory();
		$inventory->order_id = $order->order_id;
		$inventory->store_code = $order->store_code;
		$inventory->product_code = $order->product_code;
		$inventory->inv_batch_number = $batch_number;
		$inventory->unit_code = $order->product->unit_code;

		$uom = ProductUom::where('product_code', $order->product_code)
				->where('unit_code', $inventory->unit_code)
				->first();

		$inventory->uom_rate =  $uom->uom_rate;
		$inventory->inv_unit_cost =  $uom->uom_cost;
		$inventory->inv_quantity = -($total_supply*$uom->uom_rate);
		$inventory->inv_physical_quantity = $total_supply;
		$inventory->inv_subtotal =  $uom->uom_cost*$inventory->inv_physical_quantity;
		$inventory->move_code = 'sale';
		$inventory->inv_posted = 1;
		$inventory->save();
				
	}
}

?>
