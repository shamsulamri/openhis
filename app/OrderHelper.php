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
							$order_drug->frequency_code = $drug_prescription->frequency_code;
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
	}	

		public static function hasOpenOrders($user_id)
		{
				$orders = Order::where('user_id', $user_id)
							->distinct('encounter_id')
							->where('post_id',0)
							->groupBy('encounter_id')
							->get();


				if ($orders) {
					return $orders;
				} else {
					return null;
				}
		}
}

?>
