<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
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
use App\MedicationRecord;

class OrderHelper 
{
	public function getEncounter($encounter_id) 
	{
		$encounter = Encounter::find($encounter_id);
		return $encounter;
	}

	public static function getOrder($order_id) 
	{
			$order = Order::find($order_id);
			return $order;
	}

	public static function getProduct($product_code) 
	{
			$product = Product::find($product_code);
			return $product;
	}

	public static function getOrderByConsultation($product_code) {
			$product_code = $product_code;
			$encounter_id = Session::get('encounter_id');
			$consultation_id = Session::get('consultation_id');
			$order = Order::where('product_code','=', $product_code)
						->where('encounter_id','=', $encounter_id)
						->where('consultation_id','=', $consultation_id)
						->where('order_completed','=','0')
						->first();

			return $order;
	}

	public static function hasDischargeDrugs($encounter_id) {
			$discharge_orders = Order::where('encounter_id', $encounter_id)
					->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
					->where('order_is_discharge', 1)
					->where('category_code', 'drugs')
					->count();

			Log::info($discharge_orders);
			return $discharge_orders>0?true:false;
	}

	public function getPrescription($order_id)
	{
			$drug = OrderDrug::where('order_id', $order_id)->first();

			$value = "";
			if ($drug) {
					if (!empty($drug->drug_strength)) $value = $value . $drug->drug_strength;
					if (!empty($drug->unit)) $value = $value . " ".$drug->unit->unit_name;
					if (!empty($drug->dosage_code)) $value = $value.", ".$drug->drug_dosage;
					if (!empty($drug->dosage)) $value = $value." ".$drug->dosage->dosage_name;
					if (!empty($drug->route_code)) $value = $value.", ".$drug->route->route_name;
					if (!empty($drug->frequency_code)) $value = $value.", ".$drug->frequency_code;
					if (!empty($drug->drug_duration)) $value = $value.", ".$drug->drug_duration;
					if (!empty($drug->period_code)) $value = $value." ".$drug->period_code;
					$value = trim($value, ",");
			}

			return $value;
	}

	public function getDrugUnitCount($order_id)
	{
			$drug = OrderDrug::where('order_id', $order_id)->first();

			$value = "";
			if ($drug) {
					$dosage = $drug->drug_dosage;
					$frequency = $drug->frequency->frequency_value;
					$period = $drug->period->period_value;
			}

			return $value;
			/*
			dosage = document.getElementById('dosage').value;
			frequency = getFrequencyValue(document.getElementById('frequency').value) 
			period = getPeriodValue(document.getElementById('period').value) 
			duration = document.getElementById('duration').value;
			total = frequency*dosage;
			if (duration>0) total = total*duration*period;
			if (isNumber(total)==true) {
				document.getElementById('total').value=total;
			}
			 */
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

	public static function getTargetLocation($product, $consultation=null)
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

	public static function getTargetStore($product, $consultation=null)
	{
			$store_code = null;
			if ($product->product_stocked==1) {
					$encounter = Encounter::find(Session::get('encounter_id'));
					$admission = EncounterHelper::getCurrentAdmission(Session::get('encounter_id'));
					//$location_code = (new self)->getTargetLocation($product);

					if (!empty($location_code)) {
								$store_code = QueueLocation::find($location_code)->store_code;
					} else {
								$store_code = (new self)->getLocalStore($encounter, $admission, $consultation);
					}

					if ($product->product_local_store==1) {
								$store_code = (new self)->getLocalStore($encounter, $admission, $consultation);
					}
			}

			return $store_code;
	}

	public static function getLocalStore($encounter, $admission, $consultation=null)
	{
				$store_code = null;
				if ($admission) {
						$store_code = $admission->bed->ward->store_code;
						if (!empty($consultation)) {
							$ward = Ward::find($consultation->transit_ward);
							$store_code = $ward->store_code;
						}
				} else {
						$location = Queue::where('encounter_id', '=', $encounter->encounter_id)->first()->location;
						if ($location) $store_code = $location->store_code;
				}
				return $store_code;
	}

	public static function orderItem($product, $ward_code, $renew_drug=null)
	{
			if ($product->status_code != 'active') {
				return;
			}

			$consultation = Consultation::find(Session::get('consultation_id'));

			$admission = EncounterHelper::getCurrentAdmission(Session::get('encounter_id'));

			$order = Order::where('consultation_id', Session::get('consultation_id'))
						->where('encounter_id', Session::get('encounter_id'))
						->where('product_code', $product->product_code)
						->where('post_id','=',0)
						->first();

			if (!empty($order)) {
				/** Allow similar item to be reorder as new order instead of increasing the quantity **/
				if ($product->category_code == 'drugs' or $product->category_code == 'drug_generic' or $product->category_code == 'drug_trade') {
					$order = null;
				}
				if ($product->category_code == 'fee_procedure') {
					$order = null;
				}
			}

			if (empty($order)) {
				/** New order **/
				$order = new Order();
				$order->order_quantity_request = 1;
				$order->order_quantity_supply = 1;
			} else {
				/** Update existing order **/
				$order->order_quantity_request += 1;
				$order->order_quantity_supply = $order->order_quantity_request;
			}

			if ($product->category_code == 'imaging2') {
				$order = new Order();
				$order->order_quantity_request = 1;
				$order->order_quantity_supply = 1;
			}

			$order->consultation_id = Session::get('consultation_id');
			$order->encounter_id = Session::get('encounter_id');
			$order->user_id = Auth::user()->id;

			$encounter = Encounter::find($order->encounter_id);
			$default_price = $product->uomDefaultPrice($encounter);

			$order->unit_code = $default_price?$default_price->unit_code:'unit';
			$order->order_unit_price = $default_price?$default_price->uom_price:'unit';

			$order->product_code = $product->product_code;

			if ($admission) {
					$order->admission_id = $admission->admission_id;
					$order->ward_code = $admission->bed->ward_code;
			}

			$location_code = (new self)->getTargetLocation($product);
			$order->location_code = $location_code;
			$order->store_code = (new self)->getTargetStore($product);
			$order->order_discount = (new self)->getDiscountAmount($order->encounter_id, $product->product_code);

			/** Overide: Transit Ward Order **/
			if (!empty($consultation->transit_ward)) {
				$order->ward_code = $consultation->transit_ward;
				$order->store_code = (new self)->getTargetStore($product, $consultation);
			}

			$order->save();

			if ($product->order_form==2) {
					$order_drug = new OrderDrug();
					$order_drug->order_id = $order->order_id;
					if (empty($renew_drug)) {
							$drug_prescription = DrugPrescription::where('drug_code', $product->product_code)->first();
							if (empty($drug_prescription)) {
									$drug_prescription = DrugPrescription::where('drug_code', $product->product_custom_id)->first();
							}
							if ($drug_prescription) { 
									
									$order_drug->drug_strength = $drug_prescription->drug->strength;
									$uom = UnitMeasure::find($drug_prescription->drug->uom_strength);
									if (!empty($uom)) {
											$order_drug->unit_code = $drug_prescription->drug->uom_strength;
									}
									$order_drug->drug_dosage = $drug_prescription->drug_dosage;
									$order_drug->dosage_code = $drug_prescription->dosage_code;
									$order_drug->route_code = $drug_prescription->route_code;
									//$order_drug->frequency_code = $drug_prescription->frequency_code;
									$order_drug->drug_duration = $drug_prescription->drug_duration;
									$order_drug->period_code = $drug_prescription->period_code;
									$order->order_quantity_request = $drug_prescription->drug_total_unit;
									$order->order_quantity_supply = $drug_prescription->drug_total_unit;
									$order->order_description = $drug_prescription->drug_description;


							} 

							if ($order->product->product_unit_charge==1) {
									//$order->order_quantity_request = 0;
									//$order->order_quantity_supply = 0;
							} else {
									$order->order_quantity_request = 1;
									$order->order_quantity_supply = 1;
							}

					} else {
							$order_drug->drug_strength = $renew_drug->drug_strength;
							$order_drug->unit_code = $renew_drug->unit_code;
							$order_drug->drug_dosage = $renew_drug->drug_dosage;
							$order_drug->dosage_code = $renew_drug->dosage_code;
							$order_drug->route_code = $renew_drug->route_code;
							$order_drug->frequency_code = $renew_drug->frequency_code;
							$order_drug->drug_duration = $renew_drug->drug_duration;
							$order_drug->period_code = $renew_drug->period_code;

							$order->order_quantity_request = $renew_drug->order->order_quantity_request;

							if ($order_drug->dosage_code) {
									$dosage = DrugDosage::find($order_drug->dosage_code)->first();

									if ($dosage) {
											if ($dosage->dosage_unit_count==1) {
													$dosage = $order_drug->drug_dosage;
													$frequency = $order_drug->frequency?$order_drug->frequency->frequency_value:1;
													$period = $order_drug->period?$order_drug->period->period_mins:1;

													$total_unit = $dosage*$frequency;

													if ($order_drug->drug_duration>0) {
														$total_unit = $total_unit * (($order_drug->drug_duration*$period)/1440);
													}
													$order->order_quantity_request = $total_unit;
													$order->order_quantity_supply = $total_unit;
											}
									}
							}
					}

					$order->save();
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
							$description .= $order_drug->drug_strength;
							if ($order_drug->unit) { $description .= " ".$order_drug->unit->unit_name.", "; }
					}

					if ($order_drug->drug_dosage>0) {
							$description .= $order_drug->drug_dosage;
							if ($order_drug->dosage) { $description .= " ".$order_drug->dosage->dosage_name.", "; }
					}

					if ($order_drug->route) {
						$description .= $order_drug->route->route_name .", ";
					}

					if ($order_drug->frequency) {
						$description .= $order_drug->frequency->frequency_name.", ";
					}

					if ($order_drug->drug_duration>0) {
							$description .= $order_drug->drug_duration;
							if ($order_drug->period) {
						   		$description .= " ".strtolower($order_drug->period->period_name);
							}
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

			if ($order->product_drop_charge==1) $drop_now = true;

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
									//self::addToInventory($order, $batch->inv_batch_number);
							}
						} else {
							// Replace with addDropChargeSales in BillController
							//self::addToInventory($order);
						}

				}
			}
		}
	}

	public static function addToInventory($order, $batch_number = null) 
	{
		$uom = ProductUom::where('product_code', $order->product_code)
					->where('uom_default_price', 1)
					->first();

		Log::info($uom);

		if ($uom) {
				$inventory = new Inventory();
				$inventory->order_id = $order->order_id;
				$inventory->store_code = $order->store_code;
				$inventory->product_code = $order->product_code;
				$inventory->unit_code = $order->unit_code;
				$inventory->uom_rate =  $uom->uom_rate;
				$inventory->unit_code = $uom->unit_code;
				$inventory->inv_unit_cost =  $uom->uom_cost?:0;
				$inventory->inv_quantity = -($order->order_quantity_supply*$uom->uom_rate);
				$inventory->inv_physical_quantity = $order->order_quantity_supply;
				$inventory->inv_subtotal =  -($uom->uom_cost*$inventory->inv_physical_quantity);
				$inventory->move_code = 'sale';
				$inventory->inv_batch_number = $batch_number;
				$inventory->inv_posted = 1;
				$inventory->save();
				Log::info($inventory);
		}
	}

	public static function addToInventory2($order, $batch_number = null) 
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

	public static function orderBOM($consultation_id)
	{
			$order_helper = new OrderHelper();
			$orders = Order::where('consultation_id', $consultation_id)
						->where('post_id','=',0)
						->get();

			foreach ($orders as $order) {
					if ($order->product->bom()) {
							foreach ($order->product->bom as $bom) {
								$product = $bom->product;
								$order_id = $order_helper->orderItem($product, null);
								if (!empty($order_id)) {
										$bom_order = Order::find($order_id);
										Log::info($order_id);
										Log::info("-----------");
										Log::info($order->product->product_name);
										Log::info($product->product_name);
										$bom_order->order_quantity_request = $bom->bom_quantity*$order->order_quantity_request;
										Log::info("-----------");
										$bom_order->order_quantity_supply = $bom_order->order_quantity_request;
										$bomUnitPrice = $bom->unitPrice($bom->bom_product_code, $bom->unit_code);
										if (!empty($bomUnitPrice)) {
											$bom_order->order_unit_price = $bomUnitPrice;
										} else {
											$bom_order->order_unit_price = 0;
										}
										$bom_order->unit_code = $bom->unit_code;
										$bom_order->save();

										$record = Order::find($order_id);
										$record->bom_code = $order->product_code;
										$record->save();
								}
							}
					}
			}

	}

	public function removeDuplicatePost() {

			$sql = "select count(*) as x, consultation_id from order_posts
					group by consultation_id
					having x>1
			";

			$results = DB::select($sql);

			if (!empty($results)) {
					foreach($results as $row) {
						Log::info($row->consultation_id);
						$posts = OrderPost::where('consultation_id', $row->consultation_id)
									->get();
						$flag = false;
						foreach($posts as $post) {
							if ($flag) {
								Log::info('---->'.$post->post_id);
								OrderPost::find($post->post_id)->delete();
							}
							$flag = true;
						}
					}
			}
	}

	public function removeDuplicateIx() {

			$sql = "select count(*) as x, order_id from order_investigations
					group by order_id
					having x>1
			";

			$results = DB::select($sql);

			if (!empty($results)) {
					foreach($results as $row) {
						Log::info($row->order_id);
						$orders = OrderInvestigation::where('order_id', $row->order_id)
									->get();
						$flag = false;
						foreach($orders as $order) {
							if ($flag) {
								Log::info('---->'.$order->id);
								OrderInvestigation::find($order->id)->delete();
							}
							$flag = true;
						}
					}
			}
	}

	public function marServingCount($order_id) 
	{

			$mar = MedicationRecord::where('order_id', $order_id)
						->where('medication_fail', 0)
						->get();
						
			return $mar->count();

	}

	public function marUnitCount($order_id) 
	{
			$order = Order::find($order_id);

			/*** Do not update quantity if discharge **/
			if (!empty($order->encounter->discharge)) {
				   	return $order->order_quantity_supply;
			}

			$mar = MedicationRecord::where('order_id', $order_id)
						->where('medication_fail', 0)
						->get();
						
			$total = 0;
			$orderDrug = OrderDrug::where('order_id', $order_id)->first();
			$dosage = DrugDosage::find($orderDrug->dosage_code);
			if (!empty($dosage)) {
					if ($dosage->dosage_count_unit==1) {
							$total = $mar->count()*$orderDrug->drug_dosage;
					} else {
							if ($this->marServingCount($order_id)==0) {
								$total = 0;
							} else {
								$total = 1;
							}
					}
			} else {
					$total = $mar->count();
			}

			if ($total==0) {
					$order->order_quantity_supply = 0;
			} else {
					$order->order_quantity_supply = $total;
			} 

			$order->save();

			return $total;

	}

	public function getDefaultPrice($product_code) 
	{
			$uom = ProductUom::where('product_code', $product_code)
					->where('uom_default_price', 1)
					->first();

			return $uom;
	}

	public function getDiscountAmount($encounter_id, $product_code=null)
	{
			$encounter = Encounter::findOrFail($encounter_id);
			$product = Product::where('product_code', '=', $product_code)->first();

			$rules = DiscountRule::all();

			foreach($rules as $rule) {
					if ($rule->sponsor_code == $encounter->sponsor_code) {
							if ($rule->parent_code == $product->category->parent_code) {
									return $rule->discount_amount;
							}
							if ($rule->category_code == $product->category_code) {
									return $rule->discount_amount;
							}
					}
			}

	}

}

?>
