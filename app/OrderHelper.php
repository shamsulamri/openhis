<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use App\OrderMultiple;
use App\EncounterHelper;
use App\Order;
use App\OrderPost;
use Session;
use Auth;
use Log;
use App\Ward;

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

			$value = $drug->drug_dosage." ".$drug->dosage->dosage_name;
			$value = $value.", ".$drug->route_code.", ".$drug->frequency_code;
			$value = $value.", ".$drug->drug_duration." ".$drug->period_code;
			return $value;
	}

	public static function orderItem($product, $ward_code) 
	{
			$admission = EncounterHelper::getCurrentAdmission(Session::get('encounter_id'));
			$order = new Order();
			$order->consultation_id = Session::get('consultation_id');
			$order->encounter_id = Session::get('encounter_id');
			$order->user_id = Auth::user()->id;
			if ($admission) {
				$order->admission_id = $admission->admission_id;
			}

			$order->product_code = $product->product_code;
			$order->order_quantity_request = 1;
			$order->order_unit_price = $product->product_sale_price; 
			if ($product->tax) {
				$order->order_sale_price = number_format($product->product_sale_price * (1+($product->tax->tax_rate/100)),2);
			} else {
				$order->order_sale_price = number_format($product->product_sale_price,2);
			}	
			$order->order_total = $order->order_sale_price*$order->order_quantity_request;
			$order->location_code = $product->location_code;
			if ($product->product_drop_charge==1) {
					$ward = Ward::where('ward_code', $ward_code)->first();
					$order->store_code = $ward->store_code;
					$order->order_completed=1;
			}
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
					OrderHelper::createDrugServings($order_drug);
					$order->order_total = $order->order_sale_price*$order->order_quantity_request;
					$order->save();
					Log::info($order);
			}

			if ($product->order_form=3) {
					$order_investigation = new OrderInvestigation();
					$order_investigation->order_id = $order->order_id;
					$order_investigation->investigation_date = date('d/m/Y');
					$order_investigation->save();
			}
			return $order->order_id;
	}	

	public static function createDrugServings($order_drug) 
	{
		if ($order_drug->order->consultation->encounter->encounter_code == 'inpatient') {
				if (!empty($order_drug->period->period_mins) && !empty($order_drug->frequency->frequency_value)) {
					$multi = OrderMultiple::where('order_id','=', $order_drug->order_id)->delete();
					$frequencies = $order_drug->frequency->frequency_value*$order_drug->drug_duration*($order_drug->period->period_mins/1440);
					
					if ($frequencies>0) {
							OrderMultiple::where('order_id',$order_drug->order_id)->delete();
							for ($i=0; $i<$frequencies; $i++) {
									$multi = new OrderMultiple();
									$multi->order_id = $order_drug->order_id;
									$multi->save();
							}
							Log::info($order_drug->order_id);
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
					Log::info($order_investigation->order_id);
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

					return $prefix.$description;
			}
		}
}

?>
