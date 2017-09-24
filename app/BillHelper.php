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
			return $bill_grand_total+$deposit_total-$payment_total;
	}
}
