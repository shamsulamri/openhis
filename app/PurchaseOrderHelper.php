<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use Log;

class PurchaseOrderHelper 
{
	
	public function test() 
	{
			return "xxx";
	}

	public function open()
	{

			$sql = "select count(*) as total from purchase_orders where purchase_posted=0 and deleted_at is null";

			$results = DB::select($sql);
			
			return $results[0]->total;

	}

	public function posted()
	{

			$sql = "select count(*) as total from purchase_orders where purchase_posted=1 and deleted_at is null";

			$results = DB::select($sql);
			
			return $results[0]->total;

	}
}
