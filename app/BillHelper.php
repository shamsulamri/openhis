<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use App\BillItem;
use Log;

class BillHelper 
{

	public function paymentOutstanding($patient_id, $encounter_id, $non_claimable) 
	{
			Log::info("--------------");
			Log::info($patient_id);
			Log::info($encounter_id);
			Log::info($non_claimable);
			$bill_grand_total = DB::table('bill_items')
					->where('encounter_id','=', $encounter_id)
					->where('multi_id', 0);

			$bill_grand_total = $bill_grand_total->where('bill_non_claimable','=', $non_claimable);

			/**
			if (!empty($non_claimable)) {
				if ($non_claimable == 'true') {	
						$bill_grand_total = $bill_grand_total->where('bill_non_claimable','=', 1);
				} else {
						$bill_grand_total = $bill_grand_total->whereNull('bill_non_claimable');
				}
			}
			**/

			$bill_grand_total = $bill_grand_total->sum('bill_amount');

			$bill_discount = BillDiscount::where('encounter_id', $encounter_id)->first();

			if ($bill_discount) {
				$bill_grand_total = $bill_grand_total*(1-($bill_discount->discount_amount/100));
			}

			$payment_total = DB::table('payments as a')
					->where('patient_id','=', $patient_id)
					->where('encounter_id','=', $encounter_id)
					->where('payment_non_claimable', '=', $non_claimable)
					->where('multi_id', 0)
					->sum('payment_amount');

			Log::info($payment_total);
			
			$deposit_total = DB::table('deposits as a')
					->leftjoin('encounters as b', 'a.encounter_id','=','b.encounter_id')
					->where('a.patient_id','=', $patient_id)
					->where('a.encounter_id','=', $encounter_id)
					->sum('deposit_amount');
			
			return DojoUtility::roundUp($bill_grand_total)-$deposit_total-$payment_total;
	}

	public function agingPatient($group) {

			$sql = "
				select aging_amount, age_group, total_amount, (aging_amount/total_amount)*100 as percentage, grand_total, (total_amount/grand_total)*100 as total_percentage
				from (
				select sum(age_amount) as aging_amount, age_group
				from bill_agings
				where age_group=". $group . "
				and sponsor_code is null
				) as x
				cross join (
					select sum(age_amount) as total_amount from bill_agings where sponsor_code is null
				) as y
				cross join (
					select sum(age_amount) as grand_total from bill_agings 
				) as z
			";

			$results = DB::select($sql);

			if (!empty($results)) {
				Log::info($results[0]->aging_amount);
				return $results;
			} else {
				return 0;
			}

	
	}

	public function agingSponsor($group) {

			$sql = "
				select aging_amount, age_group, total_amount, (aging_amount/total_amount)*100 as percentage, grand_total, (total_amount/grand_total)*100 as total_percentage
				from (
				select sum(age_amount) as aging_amount, age_group
				from bill_agings
				where age_group=". $group . "
				and sponsor_code is not null
				) as x
				cross join (
					select sum(age_amount) as total_amount from bill_agings where sponsor_code is not null
				) as y
				cross join (
					select sum(age_amount) as grand_total from bill_agings 
				) as z
			";

			$results = DB::select($sql);

			if (!empty($results)) {
				Log::info($results[0]->aging_amount);
				return $results;
			} else {
				return 0;
			}

	
	}

	public function agingTotal($group) {

			$sql = "
				select aging_amount, age_group, total_amount, (aging_amount/total_amount)*100 as percentage, grand_total, (total_amount/grand_total)*100 as total_percentage
				from (
				select sum(age_amount) as aging_amount, age_group
				from bill_agings
				where age_group=". $group . "
				) as x
				cross join (
					select sum(age_amount) as total_amount from bill_agings 
				) as y
				cross join (
					select sum(age_amount) as grand_total from bill_agings 
				) as z
			";

			$results = DB::select($sql);

			if (!empty($results)) {
				Log::info($results[0]->aging_amount);
				return $results;
			} else {
				return 0;
			}

	
	}

	public function billStatus($encounter_id)
	{
			/*
			 * Bill status
			 * 0 = open
			 * 1 = close
			 */
			$encounter = Encounter::find($encounter_id);

			$status = 0;

			if (!empty($encounter->sponsor_code)) {

					$bill_claimable = False;
					$bill_non_claimable = False;

					$claimables = BillItem::where('encounter_id', '=', $encounter_id)
									->where('bill_non_claimable', '=', 0)
									->get();

					if ($claimables->count()>0) {
							$bill = Bill::where('encounter_id', '=', $encounter_id)
										->where('bill_non_claimable', '=', 0)
										->get(); 	
							if ($bill->count()>0) {
									$bill_claimable = True;
							}
					}
					
					$non_claimables = BillItem::where('encounter_id', '=', $encounter_id)
									->where('bill_non_claimable', '=', 1)
									->get();

					if ($non_claimables->count()>0) {
							$bill = Bill::where('encounter_id', '=', $encounter_id)
										->where('bill_non_claimable', '=', 1)
										->get(); 	
							if ($bill->count()>0) {
									$bill_non_claimable = True;
							}
					}

					if ($bill_claimable && $bill_non_claimable) {
							$status = 1;
					} else {
							if (!$bill_claimable && !$bill_non_claimable) {
									$status = 0;
							}
							if ($bill_claimable && !$bill_non_claimable) {
									$status = 3;
							}
							if (!$bill_claimable && $bill_non_claimable) {
									$status = 2;
							}
					}

			} else {
					$bill = Bill::where('encounter_id', '=', $encounter_id)->get(); 	

					if ($bill->count()>0) {
							$status = 1;
					}
			}

			return $status;
			
	}
}
