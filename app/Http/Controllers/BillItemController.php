<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BillItem;
use App\Bill;
use Log;
use DB;
use Session;
use App\Product;
use App\QueueLocation as Location;
use App\Encounter;
use App\DojoUtility;
use App\Order;
use App\OrderMultiple;
use Carbon\Carbon;
use App\FormValue;
use App\Form;
use App\ProductPriceTier;
use App\ProductCategory;
use App\BillDiscount;
use App\BedMovement;
use App\Bed;
use App\MedicalCertificate;
use App\ProductUom;
use App\BillTotal;
use App\Consultation;
use Auth;
use App\BedCharge;
use App\Payment;

class BillItemController extends Controller
{
	public $paginateValue=1000;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function generate($id) 
	{
			$bill_existing = DB::table('bill_items')
								->where('encounter_id','=',$id);
								
			$bill_existing->delete();
			$this->updateOrderPrices($id);
			return redirect('/bill_items/'.$id);
	}

	public function bedBills($encounter_id) {
			$encounter = Encounter::find($encounter_id);
			$sql = "
				select bed_code, bed_stop, datediff(bed_stop, bed_start) as los, datediff(now(),bed_start) as los2, b.product_code, c.tax_code, tax_rate, uom_price as product_sale_price, block_room, product_name 
				from bed_charges as a
				left join products as b on (a.bed_code = product_code)
				left join tax_codes as c on (c.tax_code = b.product_output_tax)
				left join discharges as d on (d.encounter_id = a.encounter_id)
				left join product_uoms as g on (g.product_code = b.product_code and g.unit_code = b.unit_code)
				where a.encounter_id=%d
			";

			$sql = sprintf($sql, $encounter_id);
			$beds = DB::select($sql);

			foreach ($beds as $bed) {

					$bed_los = $bed->los;
					$bed_unit = 1;

					if (empty($bed->bed_stop)) {
							$bed_los = $bed->los2;
					}

					if ($bed_los<=0) $bed_los=1;

					$block_room = $bed->block_room;

					if ($block_room == 1) {
							$bed_charge = Bed::find($bed->bed_code);
							if ($bed_charge->room) {
									$bed_unit = $bed_charge->room->beds->count();
							}
							if ($bed_unit == 0) $bed_unit = 1;
					}

					$item = new BillItem();
					$item->encounter_id = $encounter_id;
					$item->product_code = $bed->product_code;
					$item->tax_code = $bed->tax_code;
					$item->tax_rate = $bed->tax_rate;
					$item->bill_name = $bed->product_name;
					$item->bill_quantity = $bed_los*$bed_unit;
					$item->bill_unit_price = $this->getPriceTier($encounter_id, $bed->product_code, $bed->product_sale_price);
					$item->bill_amount_exclude_tax = $item->bill_unit_price*$item->bill_quantity;
					$item->bill_amount = $item->bill_unit_price*$item->bill_quantity;
					$item->bill_non_claimable = 2;

					if (!empty($encounter->sponsor_code)) {
						$item->bill_non_claimable = 0;
					}

					if ($bed->tax_rate) {
						$item->bill_amount = $item->bill_amount*(($bed->tax_rate/100)+1);
					}

					$item->save();
					Log::info($item);

					/*** Merge ***/
					$merge_item = new BillItem();
					$merge_item->encounter_id = $item->encounter_id;
					$merge_item->product_code = $item->product_code;
					$merge_item->bill_name = $item->bill_name;
					$merge_item->tax_code = $item->tax_code;
					$merge_item->tax_rate = $item->tax_rate;
					$merge_item->bill_unit_price = $item->bill_unit_price;
					$merge_item->bill_non_claimable = 2;
					if (!empty($encounter->sponsor_code)) {
						$item->bill_non_claimable = 0;
					}

					$bill_items = BillItem::where('product_code',$item->product_code)
							->where('encounter_id', '=', $encounter_id)
							->get();

					/*** Sum-up selected fields ***/
					foreach($bill_items as $bill_item) {
						$merge_item->bill_quantity += $bill_item->bill_quantity;
						$merge_item->bill_amount_exclude_tax += $item->bill_amount_exclude_tax;
						$merge_item->bill_amount += $item->bill_amount;
					}

					/*** Remove duplicate beds ***/
					BillItem::where('product_code', $merge_item->product_code)
							->where('encounter_id', $encounter_id)
							->delete();

					/*** Save merge beds ***/
					if (!empty($encounter->sponsor_code)) {
							$merge_item->bill_non_claimable = 0;
					}
					$merge_item->save();

					$consultation = Consultation::where('encounter_id', $encounter_id)->orderBy('created_at', 'desc')->first();

					Order::where('order_custom_id', $merge_item->product_code)
							->where('encounter_id', $encounter_id)
							->delete();

					$order = new Order();
					$order->product_code = $merge_item->product_code;
					$order->order_custom_id = $merge_item->product_code;
					$order->encounter_id = $merge_item->encounter_id;
					$order->order_quantity_supply = $merge_item->bill_quantity;
					$order->order_unit_price = $merge_item->bill_unit_price;
					$order->consultation_id = $consultation->consultation_id;
					$order->user_id = Auth::user()->id;
					$order->save();




			}
	}

	public function updateBedOrder($encounter_id) 
	{
			$bed_charges = BedCharge::where('encounter_id', $encounter_id)->distinct()->get(['bed_code']);
			foreach ($bed_charges as $bed) {
					Log::info($bed->product_code);
					Order::where('order_custom_id', $bed->bed_code)
							->where('encounter_id', $encounter_id)
							->delete();
			}
			
			/*
			$consultation = Consultation::where('encounter_id', $encounter_id)->orderBy('created_at', 'desc')->first();

			$bed_orders = BillItem::where('encounter_id', $encounter_id)
						->leftJoin('products as b', 'b.product_code', '=', 'bill_items.product_code')
						->where('category_code', 'bed')
						->get();

			foreach($bed_orders as $bed_order) {
					Order::where('order_custom_id', $bed_order->product_code)
							->where('encounter_id', $encounter_id)
							->delete();
					$order = new Order();
					$order->product_code = $bed_order->product_code;
					$order->order_custom_id = $bed_order->product_code;
					$order->encounter_id = $bed_order->encounter_id;
					$order->order_quantity_supply = $bed_order->bill_quantity;
					$order->order_unit_price = $bed_order->bill_unit_price;
					$order->consultation_id = $consultation->consultation_id;
					$order->user_id = Auth::user()->id;
					$order->save();
			}
			 */
	}

	public function getPriceTier($encounter_id, $product_code, $price=null)
	{
			$encounter = Encounter::find($encounter_id);
			$product = Product::find($product_code);

			if ($product->uomDefaultPrice($encounter)) {
				$price = $product->uomDefaultPrice($encounter)->uom_price?:0;
			}

			return $price;

			$price = 1;
			if (empty($product->charge_code)) {
					if ($product->uomDefaultPrice($encounter)) {
						$price = $product->uomDefaultPrice($encounter)->uom_price?:0;
					}
			}

			$value=0;
			/*
			$tiers = ProductPriceTier::where('charge_code','=', $product->charge_code)->get();

			if (empty($tiers)) {
					return $price;
			}

			if (empty($price)) {
					if ($product->uomDefaultPrice($encounter)) {
						$price = $product->uomDefaultPrice($encounter)->uom_price?:0;
					}
			}

			if ($tiers->count()>0) {
					foreach ($tiers as $tier) {
						if ($price<=$tier->tier_max && empty($tier->tier_min)) {
							break;
						} 
						if ($price>$tier->tier_min && $price<=$tier->tier_max) {
							break;
						} 
						if ($price>$tier->tier_min && empty($tier->tier_max)) {
							break;
						}
					}
			} else {
					$tier = $tiers[0];
			}
			 */

			/*** Outpatient vs Inpatient ***/
			if ($encounter->encounter_code=='outpatient' or $encounter->encounter_code=='emergency') {
					if (!empty($tier->tier_outpatient_markup)) {
							$value = $price + $tier->tier_outpatientt_markup;
					} elseif (!empty($tier->tier_outpatient_multiplier)) {
							$value = $tier->tier_outpatient_multiplier*$price;
							if (!empty($tier->tier_outpatient_limit)) {
								if ($value>$tier->tier_outpatient_limit) $value = $tier->tier_outpatient_limit;
							}
					} elseif (!empty($tier->tier_outpatient)) {
							$value = $tier->tier_outpatient;
					} else {
							$value = $price;
					}

			}

			if ($encounter->encounter_code=='inpatient') {
					if (!empty($tier->tier_inptient_markup)) {
							$value = $price + $tier->tier_inpatient_markup;
					} elseif (!empty($tier->tier_inpatient_multiplier)) {
							$value = $tier->tier_inpatient_multiplier*$price;
							if (!empty($tier->tier_inpatient_limit)) {
								if ($value>$tier->tier_inpatient_limit) $value = $tier->tier_inpatient_limit;
							}
					} elseif (!empty($tier->tier_inpatient)) {
							$value = $tier->tier_inpatient;
					} else {
							$value = $price;
					}

			}

			/*** Public vs Sponsor ***/
			if ($encounter->type_code=='public') {
					if (!empty($tier->tier_public_markup)) {
							$value = $price + $tier->tier_public_markup;
					} elseif (!empty($tier->tier_public_multiplier)) {
							$value = $tier->tier_public_multiplier*$price;
							if (!empty($tier->tier_public_limit)) {
								if ($value>$tier->tier_public_limit) $value = $tier->tier_public_limit;
							}
					} elseif (!empty($tier->tier_public)) {
							$value = $tier->tier_public;
					} else {
							$value = $price;
					}

			}

			if ($encounter->type_code=='sponsored') {
					if (!empty($tier->tier_sponsor_markup)) {
							$value = $price + $tier->tier_sponsor_markup;
					} elseif (!empty($tier->tier_sponsor_multiplier)) {
							$value = $tier->tier_sponsor_multiplier*$price;
							if (!empty($tier->tier_sponsor_limit)) {
								if ($value>$tier->tier_sponsor_limit) $value = $tier->tier_sponsor_limit;
							}
					} elseif (!empty($tier->tier_sponsor)) {
							$value = $tier->tier_sponsor;
					} else {
							$value = $price;
					}

			}

			return $value;

	}


	public function updateOrderPrices($encounter_id)
	{
			$this->convertBill($encounter_id);
			$orders = Order::where('encounter_id', $encounter_id)
						->leftJoin('products as b', 'b.product_code', '=', 'orders.product_code')
						->where('product_edit_price','=',0)
						->get();

			foreach ($orders as $order) {
					$order->order_unit_price = $this->getPriceTier($encounter_id, $order->product_code, $order->order_unit_price);
					//if ($order->order_quantity_supply==0) $order->order_quantity_supply=1;
					$order->save();
			}

	}


	public function convertBill($encounter_id) {
			Log::info("Check for converts....");
			$encounter = Encounter::find($encounter_id);
			$last_encounter = Encounter::where('patient_id', $encounter->patient_id)
					->where('encounter_id', '<>', $encounter_id)
					->orderBy('encounter_id','desc')
					->first();

			if ($last_encounter) {
					$payment = Payment::where('encounter_id', $last_encounter->encounter_id)
							->where('payment_code', 'convert')
							->first();

					if ($payment) {
							if ($payment->payment_code == 'convert') {
									Log::info("Convert to inpatient....".$last_encounter->encounter_id);

									Order::where('origin_id','=',$last_encounter->encounter_id)
											->delete();

									$orders = Order::where('encounter_id', $last_encounter->encounter_id)->get();

									foreach($orders as $order) {
											$convert_order = $order->replicate();
											$convert_order->encounter_id = $encounter_id;
											$convert_order->origin_id = $last_encounter->encounter_id;
											$convert_order->save();
										Log::info($order->encounter_id);
									}

							}
					}
			}
	}

	public function compileBill($encounter_id, $non_claimable=2) 
	{
			$encounter = Encounter::find($encounter_id);
			$patient_id = $encounter->patient_id;

			$base_sql = "
				select a.product_code, a.unit_code, sum(order_quantity_supply) as total_quantity, c.tax_rate, c.tax_code, order_discount, order_unit_price, order_markup, bill_markup, product_name, bom_code
				from orders as a
				left join products as b on b.product_code = a.product_code 
				left join tax_codes as c on c.tax_code = b.product_output_tax 
				left join bill_items as f on (f.encounter_id=a.encounter_id and f.product_code = a.product_code)
				left join encounters as g on (g.encounter_id=a.encounter_id)
				left join patients as h on (h.patient_id = g.patient_id)
				left join ref_encounter_types as i on (i.encounter_code = g.encounter_code)
				left join users as j on (j.id = a.user_id)
				left join order_cancellations as k on (k.order_id = a.order_id)
				where b.deleted_at is null
				%s
				and b.category_code<>'consultation'
				and b.category_code<>'fee_procedure'
				and b.category_code<>'fee_consultation'
				and b.category_code<>'wv'
				and h.patient_id = %d
				and a.encounter_id <= %d
				and bill_id is null 
				and order_multiple=0
				and bom_code is null
				and cancel_id is null
				and order_quantity_supply>0
				group by product_code,a.unit_code, order_discount
			";

			$sql = sprintf($base_sql, "", $patient_id, $encounter_id);

				//and h.patient_id = %d
			if (!empty($encounter->sponsor_code)) {
					if ($non_claimable==1) {
						$sql = sprintf($base_sql, "and product_non_claimable = 1", $patient_id, $encounter_id);
					}

					if ($non_claimable==0) {
						$sql = sprintf($base_sql, "and product_non_claimable<>1", $patient_id, $encounter_id);
					}
			}
			
			$orders = DB::select($sql);
			
			foreach ($orders as $order) {
					$item = new BillItem();
					//$item->order_id = $order->order_id;
					$item->encounter_id = $encounter_id;
					$item->product_code = $order->product_code;
					$item->bill_name = $order->product_name;
					$item->tax_code = $order->tax_code;
					$item->tax_rate = $order->tax_rate;
					$item->bill_quantity = $order->total_quantity?:1;
					$item->bill_unit_code = $order->unit_code;
					$item->bill_discount = $order->order_discount;
					$item->bill_markup = $order->order_markup;
					$item->bill_non_claimable = $non_claimable;
					$item->bill_unit_price = $order->order_unit_price;
					//$item->bill_unit_price = $this->getPriceTier($encounter_id, $order->product_code, $order->order_unit_price);

					/*
					if (!empty($order->bom_code)) {
							$uom = ProductUom::where('product_code','=',  $order->product_code)
											->where('unit_code', '=', $order->unit_code)
											->first();

							$item->bill_unit_price = $uom->uom_price;
							Log::info('--->'.$item->bill_unit_price);
					}
					 */

					$item->bill_amount = ($order->total_quantity?:1)*$item->bill_unit_price;
					$item->bill_amount = $item->bill_amount * (1-($item->bill_discount/100));
					$item->bill_amount = $item->bill_amount * (1+($item->bill_markup/100));
					$item->bill_amount_exclude_tax = $item->bill_amount;

					if ($order->tax_rate) {
						$item->bill_amount = $item->bill_amount*(($order->tax_rate/100)+1);
					}
					try {
							$item->save();
					} catch (\Exception $e) {
							\Log::info($e->getMessage());
					}
			}

			//$this->multipleOrders($encounter_id);
			//$this->outstandingCharges($encounter_id);
	}

	public function minusPackages($encounter_id) 
	{
			/** Substract amount items from package **/
			$orders = Order::where('encounter_id', $encounter_id)
						->whereNotNull('bom_code')
						->get();


			foreach ($orders as $order) {
						$billItem = BillItem::where('product_code', $order->product_code)
										->where('bill_unit_code', $order->unit_code)
										->where('encounter_id', $encounter_id)
										->first();
						//foreach ($billItems as $billItem) {
								$billItem->bill_amount -= $order->order_quantity_supply*$order->order_unit_price;
								$billItem->bill_amount_exclude_tax = $billItem->bill_amount;
								$billItem->bill_name = $billItem->bill_name.' *';
								$billItem->save();
						//}
			}

			/*
			foreach ($orders as $order) {
					if ($order->product->bom->count()>0) {
							$boms = $order->product->bom;
							foreach ($boms as $bom) {
								$product = Product::where('product_code', $bom->bom_product_code)->first();

								$billItems = BillItem::where('product_code', $bom->bom_product_code)
												->where('encounter_id', $encounter_id)
												->get();
								Log::info('--'.$bom->bom_product_code);
								foreach ($billItems as $billItem) {
									$billItem->bill_quantity -= $bom->bom_quantity*$order->order_quantity_supply;
									$billItem->bill_amount -= $bom->bill_quantity*$billItem->bill_unit_price;
									$billItem->bill_amount_exclude_tax = $billItem->bill_amount;
									$billItem->bill_name = $billItem->bill_name.' *';
									if ($billItem->bill_quantity==0) {
											$billItem->bill_quantity = 1;
									}
									$billItem->save();
								}
							}
					}
			}
			 */

	}

	public function compileProcedure($encounter_id, $non_claimable=2) 
	{
			$encounter = Encounter::find($encounter_id);
			$patient_id = $encounter->patient_id;

			$base_sql = "
				select a.product_code, sum(order_unit_price*((100-IFNULL(order_discount,0))/100)) as order_unit_price, c.tax_rate, c.tax_code, 1 as total_quantity, order_markup, order_discount, name, department_name
				from orders as a
				left join products as b on b.product_code = a.product_code 
				left join tax_codes as c on c.tax_code = b.product_output_tax 
				left join bill_items as f on (f.encounter_id=a.encounter_id and f.product_code = a.product_code)
				left join encounters as g on (g.encounter_id=a.encounter_id)
				left join patients as h on (h.patient_id = g.patient_id)
				left join ref_encounter_types as i on (i.encounter_code = g.encounter_code)
				left join users as j on (j.id = a.user_id)
				left join departments as k on (k.department_code = j.department_code)
				left join order_cancellations as l on (l.order_id = a.order_id)
				where b.deleted_at is null
				%s
				and b.category_code='fee_procedure'
				and g.encounter_id = %d
				and bill_id is null 
				and order_multiple=0
				and cancel_id is null
				group by product_code,order_unit_price
			";

			//and h.patient_id = %d
			$sql = sprintf($base_sql, "", $encounter_id);


			if (!empty($encounter->sponsor_code)) {
					if ($non_claimable==1) {
						$sql = sprintf($base_sql, "and product_non_claimable = 1", $encounter_id);
					}

					if ($non_claimable==0) {
						$sql = sprintf($base_sql, "and product_non_claimable<>1", $encounter_id);
					}
			}
			
			$orders = DB::select($sql);
			
			foreach ($orders as $order) {
					$item = new BillItem();
					//$item->order_id = $order->order_id;
					$item->encounter_id = $encounter_id;
					$item->product_code = $order->product_code;
					$item->bill_name = $order->name;
					if (!empty($order->department_name)) {
						$item->bill_name .= " (".$order->department_name.")";
					}
					$item->tax_code = $order->tax_code;
					$item->tax_rate = $order->tax_rate;
					$item->bill_quantity = $order->total_quantity;
					//$item->bill_unit_code = "unit";
					$item->bill_discount = 0;
					$item->bill_markup = $order->order_markup;
					$item->bill_non_claimable = $non_claimable;
					$item->bill_unit_price = $order->order_unit_price;
					$item->bill_amount = $order->order_unit_price;
					$item->bill_amount = $item->bill_amount * (1-($item->bill_discount/100));
					$item->bill_amount = $item->bill_amount * (1+($item->bill_markup/100));
					$item->bill_amount_exclude_tax = $item->bill_amount;
					if ($order->tax_rate) {
						$item->bill_amount = $item->bill_amount*(($order->tax_rate/100)+1);
					}
					try {
							$item->save();
					} catch (\Exception $e) {
							\Log::info($e->getMessage());
					}
			}

			//$this->multipleOrders($encounter_id);
			//$this->outstandingCharges($encounter_id);
	}
	public function compileConsultation($encounter_id, $non_claimable = 2) 
	{
			$encounter = Encounter::find($encounter_id);
			$patient_id = $encounter->patient_id;

			$base_sql = "
				select a.order_id, a.product_code, order_quantity_supply as total_quantity, c.tax_rate, c.tax_code, order_discount, order_unit_price, product_name
				from orders as a
				left join products as b on b.product_code = a.product_code 
				left join tax_codes as c on c.tax_code = b.product_output_tax 
				left join bill_items as f on (f.encounter_id=a.encounter_id and f.product_code = a.product_code)
				left join encounters as g on (g.encounter_id=a.encounter_id)
				left join patients as h on (h.patient_id = g.patient_id)
				left join ref_encounter_types as i on (i.encounter_code = g.encounter_code)
				left join order_cancellations as k on (k.order_id = a.order_id)
				where (b.category_code='fee_consultation' or b.category_code = 'consultation' or b.category_code = 'wv')
				and order_completed = 1 
				and b.deleted_at is null
				and h.patient_id = %d
				and order_multiple=0
				and bill_id is null
				and cancel_is is null
				%s
			";

			$base_sql = "
				select a.product_code, sum(order_unit_price*(IFNULL((100-order_discount),100)/100)) as total_price, sum(order_quantity_supply) as total_quantity, c.tax_rate, c.tax_code,product_name
				from orders as a
				left join products as b on b.product_code = a.product_code 
				left join tax_codes as c on c.tax_code = b.product_output_tax 
				left join bill_items as f on (f.encounter_id=a.encounter_id and f.product_code = a.product_code)
				left join encounters as g on (g.encounter_id=a.encounter_id)
				left join patients as h on (h.patient_id = g.patient_id)
				left join ref_encounter_types as i on (i.encounter_code = g.encounter_code)
				where (b.category_code='fee_consultation' or b.category_code = 'consultation' or b.category_code = 'wv')
				and order_completed = 1 
				and b.deleted_at is null
				and h.patient_id = %d
				and order_multiple=0
				and bill_id is null
				%s
				group by a.product_code
			";
			$sql = sprintf($base_sql, $patient_id, "");

			if (!empty($encounter->sponsor_code)) {
					if ($non_claimable==1) {
						$sql = sprintf($base_sql, $patient_id, "and product_non_claimable = 1");
					}

					if ($non_claimable==0) {
						$sql = sprintf($base_sql, $patient_id, "and product_non_claimable<>1");
					}
			}

			$orders = DB::select($sql);

			foreach ($orders as $order) {
					Log::info('---'.$order->product_code);
					Log::info('---'.$order->total_price);
					$item = new BillItem();
					$item->encounter_id = $encounter_id;
					$item->product_code = $order->product_code;
					$item->tax_code = $order->tax_code;
					$item->tax_rate = $order->tax_rate;
					$item->bill_quantity = $order->total_quantity;
					$item->bill_name = $order->product_name;
					$item->bill_amount = $order->total_price?:0;
					$item->bill_unit_price = $order->total_price/$order->total_quantity;
					$item->bill_amount_exclude_tax = $item->bill_amount;
					if ($order->tax_rate) {
						$item->bill_amount = $item->bill_amount*(($order->tax_rate/100)+1);
					}
					$item->bill_non_claimable = $non_claimable;
					$item->save();
			}
	}

	public function multipleOrders($id) 
	{
			$sql = sprintf("
				select count(a.order_id) as order_quantity_supply, b.product_code, d.tax_code, tax_rate, uom_price as product_sale_price, d.tax_code, d.tax_rate, profit_multiplier
				from order_multiples a
				left join orders b on (b.order_id = a.order_id)
				left join products c on (c.product_code = b.product_code)
				left join tax_codes d on (d.tax_code = c.product_output_tax)
				left join encounters as g on (g.encounter_id=b.encounter_id)
				left join patients as h on (h.patient_id = g.patient_id)
				left join ref_encounter_types as i on (i.encounter_code = g.encounter_code)
				left join product_uoms as j on (j.product_code = b.product_code and j.unit_code = 'unit')
				where b.encounter_id=%d
				and c.deleted_at is null
				and a.order_completed=1
				group by a.order_id, uom_price
			", $id);

			$orders = DB::select($sql);

			foreach ($orders as $order) {
				$item = new BillItem();
				$item->encounter_id = $id;
				$item->product_code = $order->product_code;
				$item->tax_code = $order->tax_code;
				$item->tax_rate = $order->tax_rate;
				$item->bill_quantity = $order->order_quantity_supply;
				$item->bill_unit_multiplier = $order->profit_multiplier;
				$item->bill_unit_price = $order->product_sale_price*(1+($order->profit_multiplier/100));
				$item->bill_amount = $order->order_quantity_supply*$item->bill_unit_price;
				$item->bill_amount_exclude_tax = $order->order_quantity_supply*$item->bill_unit_price;
				if ($order->tax_rate) {
						$item->bill_amount = $item->bill_amount*(($order->tax_rate/100)+1);
				}

				try {
					$item->save();
				} catch (\Exception $e) {
					\Log::info($e->getMessage());
				}
			}
	}

	public function forms($encounter_id, $non_claimable = 2)
	{
			$base_sql = "
				select count(*) as order_quantity_supply, c.product_code, d.tax_code, tax_rate, uom_price as product_sale_price, d.tax_code, d.tax_rate, product_non_claimable, product_name, product_name
				from form_values a
				left join forms b on (b.form_code = a.form_code)
				left join products c on (c.product_code = b.form_code)
				left join tax_codes d on (d.tax_code = c.product_output_tax)
				left join encounters as g on (g.encounter_id=a.encounter_id)
				left join patients as h on (h.patient_id = g.patient_id)
				left join ref_encounter_types as i on (i.encounter_code = g.encounter_code)
				left join product_uoms as j on (j.product_code = c.product_code and j.unit_code = 'unit')
				where a.encounter_id=%d
				and c.deleted_at is null
				%s
				group by a.form_code, c.product_code";

			$sql = sprintf($base_sql, $encounter_id, "");

			$encounter = Encounter::find($encounter_id);
			if (!empty($encounter->sponsor_code)) {
					if ($non_claimable==1) {
						$sql = sprintf($base_sql, $encounter_id, "and product_non_claimable = 1");
					}

					if ($non_claimable==0) {
						$sql = sprintf($base_sql, $encounter_id, "and product_non_claimable<>1");
					}
			}

			$orders = DB::select($sql);
			$encounter = Encounter::find($encounter_id);

			foreach ($orders as $order) {
				$item = new BillItem();
				$item->bill_name = $order->product_name;
				$item->encounter_id = $encounter_id;
				$item->product_code = $order->product_code;
				$item->tax_code = $order->tax_code;
				$item->tax_rate = $order->tax_rate;
				$item->bill_quantity = $order->order_quantity_supply;
				//$item->bill_unit_price = $order->product_sale_price;
				if (!empty($order->product_code)) {
					$product = Product::find($order->product_code);
					$item->bill_unit_price = $product->uomDefaultPrice($encounter)->uom_price?:0;
				} else {
					$item->bill_unit_price = 0;
				}
				$item->bill_amount = $order->order_quantity_supply*$item->bill_unit_price;
				$item->bill_amount_exclude_tax = $order->order_quantity_supply*$item->bill_unit_price;
				$item->bill_non_claimable = $non_claimable;

				if ($order->tax_rate) {
						$item->bill_amount = $item->bill_amount*(($order->tax_rate/100)+1);
				}

				try {
					$item->save();
				} catch (\Exception $e) {
					\Log::info($e->getMessage());
				}
			}
	}

	public function outstandingCharges($encounter_id)
	{
			$encounter = Encounter::find($encounter_id);
			Log::info($encounter->patient->outstandingBill());

			$outstanding = abs($encounter->patient->outstandingBill());
			if (abs($outstanding)>0) {
				$item = new BillItem();
				$item->encounter_id = $encounter_id;
				$item->product_code = 'others';
				$item->bill_quantity = 1;
				$item->bill_unit_price = $outstanding; 
				$item->bill_amount = $outstanding;
				$item->bill_amount_exclude_tax = $outstanding;
				$item->save();
				Log::info($item);
			}
	}

	public function index($id, $non_claimable=null)
	{
			$this->updateBedOrder($id);
			$this->updateOrderPrices($id);
			$bill_label = "";
			$encounter = Encounter::find($id);

			if (!empty($encounter->sponsor_code)) {
				if (!empty($non_claimable)) {
						if ($non_claimable=='true') {
								$non_claimable = 1; // Claimable
						}
						if ($non_claimable=='false') {
								$non_claimable = 0; // Non-claim
						}
				} else {
						$non_claimable = 0;
				}
			} else {
				$non_claimable = 2; // Cash
			}

			$bills = DB::table('bill_items as a')
					->select('bill_id','a.encounter_id','a.product_code','product_name','a.tax_code','a.tax_rate','bill_discount','bill_quantity','bill_unit_price','bill_amount','bill_amount_exclude_tax','bill_exempted', 'product_non_claimable','category_name','b.category_code', 'unit_name', 'bill_markup', 'bill_name')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.product_output_tax')
					->leftjoin('product_categories as e', 'e.category_code', '=', 'b.category_code')
					->leftjoin('ref_unit_measures as g', 'g.unit_code', '=', 'a.bill_unit_code')
					->where('a.encounter_id','=', $id)
					->orderBy('category_name')
					->orderBy('product_name');

			$bills = $bills->where('bill_non_claimable','=', $non_claimable);
			$bills = $bills->paginate($this->paginateValue);

			if ($bills->count()==0) {
				$encounter = Encounter::find($id);
				DB::table('deposits')
						->where('patient_id', $encounter->patient_id)
						->where('encounter_code', $encounter->encounter_code)
						->update(['encounter_id' => $id]);

				if (!empty($encounter->sponsor_code)) {
					$this->compileBill($id, 1);
					$this->compileBill($id, 0);
					$this->compileProcedure($id, 1);
					$this->compileProcedure($id, 0);
					//$this->forms($id, 1);
					//$this->forms($id, 0);
					$this->compileConsultation($id, 1);
					$this->compileConsultation($id, 0);
				} else {
					$this->compileBill($id);
					$this->compileProcedure($id);
					//$this->forms($id);
					$this->compileConsultation($id);
				}

				/** Compile bed bills **/
				$this->bedBills($id);

				//$this->minusPackages($id);

				$bills = DB::table('bill_items as a')
					->select('bill_id','a.encounter_id','a.product_code','product_name','a.tax_code','a.tax_rate','bill_discount','bill_quantity','bill_unit_price','bill_amount','bill_amount_exclude_tax','bill_exempted', 'product_non_claimable','category_name','b.category_code', 'unit_name', 'bill_markup', 'bill_name')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.product_output_tax')
					->leftjoin('product_categories as e', 'e.category_code', '=', 'b.category_code')
					->leftjoin('ref_unit_measures as g', 'g.unit_code', '=', 'a.bill_unit_code')
					->where('a.encounter_id','=', $id)
					->where('bill_non_claimable','=', $non_claimable)
					->orderBy('category_name')
					->orderBy('product_name');

				$bills = $bills->paginate($this->paginateValue);
				$bill_label = "(Claimable)";
			}

			$pending = DB::table('bill_items as a')
					->select('bill_id')
					->leftjoin('products as b','b.product_code', '=', 'a.product_code')
					->leftjoin('tax_codes as c', 'c.tax_code', '=', 'b.product_output_tax')
					->where('a.encounter_id','=', $id)
					->count('bill_id');

			/**
			$bill_grand_total = DB::table('bill_items')
					->where('encounter_id','=', $id)
					->sum('bill_amount');
			**/

			$bill_grand_total = $bills->sum('bill_amount');

			$bill_total = $bill_grand_total;

			if (empty($bill_grand_total)) {
					$bill_grand_total=0;
			} 

			$payments = DB::table('payments as a')
					->leftJoin('payment_methods as b', 'b.payment_code','=','a.payment_code')
					->where('encounter_id','=', $id)
					->where('payment_non_claimable', '=', $non_claimable)
					->paginate($this->paginateValue);
			
			$payment_total = DB::table('payments')
					->where('encounter_id','=', $id)
					->where('payment_non_claimable', '=', $non_claimable)
					->sum('payment_amount');

			if (empty($payment_total)) {
					$payment_total=0;
			}

			$deposit_total = DB::table('deposits')
					->where('encounter_id','=', $id)
					->sum('deposit_amount');
			
			if (empty($deposit_total)) {
					$deposit_total=0;
			}


			$bill_change=0;
			$bill_outstanding=0;
			
			$balance = $payment_total+$deposit_total-$bill_grand_total;

			if ($balance<0) {
					$bill_outstanding = $balance;
			}

			if ($balance>0) {
					$bill_change = $balance;
			}

			$billPost = Bill::where('encounter_id','=',$id)
					->where('bill_non_claimable', '=', $non_claimable)	
					->get();
			
			$billPosted=False;
			if (count($billPost)>0) $billPosted=True;

			$hasMc = False;
			$mc = MedicalCertificate::where('encounter_id', '=', $id)->get();
			if (count($mc)>0) $hasMc = True;

			$incomplete_orders = Order::where('encounter_id','=',$id)
									->where('order_completed','=',0)
									->leftjoin('order_cancellations as b','orders.order_id','=', 'b.order_id')
									->whereNull('cancel_id')
									->count();

			/** Discount **/
			$bill_total_after_discount = $bill_total;
			$bill_discount=BillDiscount::where('encounter_id', $id)->first();
			if (!empty($bill_discount)) {
					$bill_total_after_discount = $bill_total * (1-($bill_discount->discount_amount/100));
			}

			$bill_label = "";
			if (!empty($encounter->sponsor_code)) {
				if ($non_claimable) {	
						$bill_label = "(Non Claimable)";
				} else {
						$bill_label = "(Claimable)";
				}
			}

			$claimable_amount = BillItem::where('encounter_id', '=', $id)
									->where('bill_non_claimable', '=', 0)
									->sum('bill_amount');

			$non_claimable_amount = BillItem::where('encounter_id', '=', $id)
									->where('bill_non_claimable', '=', 1)
									->sum('bill_amount');

			//$bill_total = $bill_grand_total;

			$bill_grand_total = $bill_total_after_discount - $deposit_total;

			$total_payable = DojoUtility::roundUp($bill_grand_total);

			if (empty($encounter->bill)) {
					$billFooter = BillTotal::where('encounter_id', $id);
					if ($billFooter) {
							$billFooter->delete();
					}
					$billFooter = new BillTotal();
					$billFooter->encounter_id = $id;
					$billFooter->bill_total = $bill_total;
					$billFooter->bill_deposit = $deposit_total;
					$billFooter->bill_total_after_discount = $bill_total_after_discount;
					$billFooter->bill_grand_total = $bill_grand_total;
					$billFooter->bill_total_payable = $total_payable;
					$billFooter->save();
			} else {
					$billFooter = BillTotal::where('encounter_id', $id)->first();
					$total_payable = $billFooter->bill_total_payable;
			}

			if (!empty($encounter->bill)) {
					$bill_total = $encounter->bill->bill_total;
					$bill_total_after_discount = $encounter->bill->bill_total_after_discount?:$bill_total;
					$bill_grand_total = $encounter->bill->bill_grand_total;
					$total_payable = DojoUtility::roundUp($encounter->bill->bill_grand_total);
			}

			return view('bill_items.index', [
					'bills'=>$bills,
					'billPosted'=>$billPosted,
					'bill_total'=>$bill_total,
					'bill_total_after_discount'=>$bill_total_after_discount,
					'bill_grand_total'=>$bill_grand_total,
					'patient' => $encounter->patient,
					'payments' => $payments,
					'payment_total' => $payment_total,
					'encounter' => $encounter,
					'encounter_id' => $id,
					'deposit_total' => $deposit_total,
					'bill_change' => $bill_change,
					'bill_outstanding' => $bill_outstanding,
					'pending' => $pending,
					'incomplete_orders'=>$incomplete_orders,
					'bill_discount'=>$bill_discount,
					'bill_label'=>$bill_label,
					'non_claimable'=>$non_claimable,
					'non_claimable_amount'=>$non_claimable_amount,
					'claimable_amount'=>$claimable_amount,
					'hasMc'=>$hasMc,
					'total_payable'=>$total_payable,
			]);
	}

	public function create()
	{
			$bill = new BillItem();
			return view('bill_items.create', [
					'bill' => $bill,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$bill = new BillItem();
			$valid = $bill->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bill = new Bill($request->all());
					$bill->order_id = $request->order_id;
					$bill->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bill_items/id/'.$bill->order_id);
			} else {
					return redirect('/bill_items/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bill = BillItem::findOrFail($id);
			return view('bill_items.edit', [
					'bill'=>$bill,
					'product' => Product::find($bill->product_code),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'patient' => $bill->encounter->patient,
					'encounter' => $bill->encounter,
					]);
	}

	public function update(Request $request, $id) 
	{
			$bill = BillItem::findOrFail($id);
			$bill->fill($request->input());

			$product = Product::find($bill->product_code);

			$bill->bill_amount = $bill->bill_quantity*$bill->bill_unit_price;
			$bill->bill_amount_exclude_tax = $bill->bill_amount;
			if ($bill->bill_discount>0) {
					$bill->bill_amount = $bill->bill_amount * (1-($bill->bill_discount/100));
					$bill->bill_amount_exclude_tax = $bill->bill_amount;
			}

			if ($bill->bill_markup>0) {
					$bill->bill_amount = $bill->bill_amount * (1+($bill->bill_markup/100));
					$bill->bill_amount_exclude_tax = $bill->bill_amount;
			}

			if ($bill->product->tax_code) {
					$bill->bill_amount = $bill->bill_amount * (1+($bill->product->tax->tax_rate/100));
			}


			if ($product->category_code == 'bed') {
				$floatVal = floatval($bill->bill_quantity);
				if ($floatVal>1) {
					$bill->bill_name = $product->product_name . " (x".$bill->bill_quantity.")";
				} else {
					$bill->bill_name = $product->product_name;
				}
			}

			$valid = $bill->validate($request->all(), $request->_method);

			if ($valid->passes()) {
				$bill->bill_exempted = $request->bill_exempted ?: 0;
				if ($bill->bill_exempted) $bill->bill_amount=0;
				$bill->save();
				Session::flash('message', 'Record successfully updated.');
				return redirect('/bill_items/'.$bill->encounter_id);
			} else {
				return view('bill_items.edit', [
						'bill'=>$bill,
						'patient'=>$bill->encounter->patient,
						'product' => Product::find($bill->product_code),
						'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
				])
				->withErrors($valid);			
			}

	}
	
	public function delete($id)
	{
		$bill = BillItem::find($id);
		return view('bill_items.destroy', [
			'bill'=>$bill,
			'patient'=>$bill->encounter->patient,
			]);

	}

	public function destroy($id)
	{	
		$bill = BillItem::find($id);
			BillItem::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bill_items/'.$bill->encounter_id);
	}
	
	public function reload($id)
	{
		//$bill = BillItem::where('encounter_id','=',$id)->first();
		$encounter = Encounter::find($id);
		return view('bill_items.reload', [
			'patient'=>$encounter->patient,
			'encounter'=>$encounter,
			]);

	}

	public function search(Request $request)
	{
			$bills = DB::table('orders')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('order_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('bill_items.index', [
					'bills'=>$bills,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bills = DB::table('orders')
					->where('order_id','=',$id)
					->paginate($this->paginateValue);

			return view('bill_items.index', [
					'bills'=>$bills
			]);
	}

	public function json($id)
	{
			$bill_items = BillItem::where('encounter_id','=', $id)->get();
			return $bill_items;
	}

	public function enquiry(Request $request)
	{
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$charges = Order::select(DB::raw('orders.encounter_id, patient_name, patient_mrn, product_name, d.product_code, (order_quantity_supply*order_unit_price) as total,
					orders.created_at as order_date'))
					->leftJoin('encounters as b', 'b.encounter_id', '=', 'orders.encounter_id')
					->leftJoin('patients as c', 'c.patient_id', '=',  'b.patient_id')
					->leftJoin('products as d', 'd.product_code', '=', 'orders.product_code')
					->where('order_completed',1)
					->orderBy('b.encounter_id', 'desc')
					->orderBy('orders.order_id');

			if (!empty($request->search)) {
					if (is_numeric($request->search)) {
							$charges = $charges->where('orders.encounter_id','=', $request->search);
					} else {
							$charges = $charges->where(function ($query) use ($request) {
									$query->where('patient_mrn','like','%'.$request->search.'%')
										->orWhere('patient_name', 'like','%'.$request->search.'%');
							});
					}
			}

			if (!empty($request->category_code)) {
					$charges = $charges->where('d.category_code','=', $request->category_code);
			}

			if (!empty($date_start) && empty($request->date_end)) {
				$charges = $charges->where('orders.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($date_start) && !empty($request->date_end)) {
				$charges = $charges->where('orders.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($date_start) && !empty($date_end)) {
				$charges = $charges->whereBetween('orders.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if ($request->export_report) {
				DojoUtility::export_report($charges->get());
			}

			$charges = $charges->paginate($this->paginateValue);

			$categories = ProductCategory::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('','');

			return view('bill_items.enquiry', [
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'charges'=>$charges,
					'search'=>$request->search,
					'categories'=>$categories,
					'category_code'=>$request->category_code,
			]);
	}

}
