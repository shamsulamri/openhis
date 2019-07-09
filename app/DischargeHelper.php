<?php

namespace App;
use Carbon\Carbon;
use DB;
use Log;

class DischargeHelper 
{
	public function drugCompleted($id)
	{

			$sql = sprintf("
						select count(*) as drugCount from orders a
						left join products b on (a.product_code = b.product_code)
						left join order_cancellations c on (c.order_id = a.order_id)
						where encounter_id = %d
						and (b.category_code = 'drugs' or b.category_code = 'drug_generics')
						and c.cancel_id is null
						and order_completed=0
					", $id);

			$results = DB::select($sql);
			
			if ($results[0]->drugCount>0) {
					return False;
			} else {
					return True;
			}

	}

	public function estimatedCost($id)
	{
			$sql = sprintf("
						select sum(order_quantity_request*order_unit_price) as cost
						from orders
						where encounter_id = %d
					", $id);

			$results = DB::select($sql);
			
			return $results[0]->cost;
	}

}
