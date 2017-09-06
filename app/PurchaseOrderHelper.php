<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use Log;
use Auth;

class PurchaseOrderHelper 
{
	
	public function test() 
	{
			return "xxx";
	}

	public function open()
	{

			$sql = "select count(*) as total from purchase_orders where purchase_posted=0 and deleted_at is null and author_id=".Auth::user()->author_id;

			$results = DB::select($sql);
			
			return $results[0]->total;

	}

	public function posted()
	{

			$sql = "select count(*) as total from purchase_orders where purchase_posted=1 and deleted_at is null and author_id=".Auth::user()->author_id;

			$results = DB::select($sql);
			
			return $results[0]->total;

	}

	public function stockReceive($line_id) 
	{
			$quantity = StockInputLine::where('po_line_id','=', $line_id)
						->sum('line_quantity');

			return $quantity;

	}
}
