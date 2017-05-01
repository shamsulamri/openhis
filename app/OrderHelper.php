<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use App\OrderMultiple;
use Log;

class OrderHelper 
{
	public function getMultipleOrder($order_id) 
	{
			$orders = OrderMultiple::where('order_id', '=',$order_id)->get();
			return $orders;
	}

}
