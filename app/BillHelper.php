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
			$bill_grand_total = DB::table('bill_items')
					->where('encounter_id','=', $id)
					->sum('bill_total');

			$payment_total = DB::table('payments')
					->where('encounter_id','=', $id)
					->sum('payment_amount');
			
			$deposit_total = DB::table('deposits')
					->where('encounter_id','=', $id)
					->sum('deposit_amount');
			
			return $bill_grand_total-$payment_total+$deposit_total;
	}
}
