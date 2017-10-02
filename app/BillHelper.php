<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use Log;

class BillHelper 
{

	public function paymentOutstanding($patient_id, $encounter_id) 
	{
			$bill_grand_total = DB::table('bill_items')
					->where('encounter_id','=', $encounter_id)
					->sum('bill_total');

			$payment_total = DB::table('payments as a')
					->where('patient_id','=', $patient_id)
					->where('encounter_id','=', $encounter_id)
					->sum('payment_amount');
			
			$deposit_total = DB::table('deposits as a')
					->leftjoin('encounters as b', 'a.encounter_id','=','b.encounter_id')
					->where('patient_id','=', $patient_id)
					->where('a.encounter_id','=', $encounter_id)
					->sum('deposit_amount');
			
			log::info($deposit_total);
			return $bill_grand_total-$deposit_total-$payment_total;
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
}
