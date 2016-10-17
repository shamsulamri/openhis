<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use Log;

class BillHelper 
{

	public function paymentOutstanding($id) 
	{
			$bill_grand_total = DB::table('bill_items as a')
					->leftjoin('encounters as b', 'a.encounter_id','=','b.encounter_id')
					->where('patient_id','=', $id)
					->sum('bill_total');

			$payment_total = DB::table('payments')
					->where('patient_id','=', $id)
					->sum('payment_amount');
			
			$deposit_total = DB::table('deposits as a')
					->leftjoin('encounters as b', 'a.encounter_id','=','b.encounter_id')
					->where('patient_id','=', $id)
					->sum('deposit_amount');
			
			return $bill_grand_total-$payment_total+$deposit_total;
	}
}
