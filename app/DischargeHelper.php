<?php

namespace App;
use Carbon\Carbon;
use DB;
use Log;
use App\DischargeSummary;

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

	public function populateSummary($id)
	{

			$discharge_summary = DischargeSummary::find($id);
			if ($discharge_summary) {
				DischargeSummary::find($id)->delete();
			}

			$discharge = Discharge::where('encounter_id', $id)->first();

			$treatments = Order::select('product_name')
					->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
					->leftjoin('order_cancellations as c', 'c.order_id', '=', 'orders.order_id')
					->where('encounter_id', $id)
					->whereIn('category_code', ['lab','imaging', 'physio_service'])
					->whereNull('cancel_id')
					->pluck('product_name');

			$drugs = Order::select('product_name')
					->where('encounter_id', $id)
					->whereIn('category_code', ['drugs'])
					->whereNull('cancel_id')
					->where('order_is_discharge', 1)
					->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
					->leftjoin('order_cancellations as c', 'c.order_id', '=', 'orders.order_id')
					->pluck('product_name');

			$procedures = Order::select('product_name')
					->where('encounter_id', $id)
					->whereIn('category_code', ['fee_procedure'])
					->whereNull('cancel_id')
					->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
					->leftjoin('order_cancellations as c', 'c.order_id', '=', 'orders.order_id')
					->pluck('product_name');

			$follow_up = Encounter::select('appointment_datetime', 'service_name')
							->leftjoin('appointments as b', 'b.patient_id', '=', 'encounters.patient_id')
							->leftjoin('appointment_services as c', 'c.service_id', '=', 'b.service_id')
							->where('encounter_id', $id)
							->where('appointment_datetime', '>', 'encounters.created_at')
							->pluck('service_name');

			$summary = new DischargeSummary();
			$summary->encounter_id = $id;
			$summary->summary_diagnosis = $discharge->discharge_summary;
			$summary->summary_treatment = $this->toList($treatments);
			$summary->summary_medication = $this->toList($drugs);
			$summary->summary_surgical = $this->toList($procedures);
			$summary->summary_follow_up = $this->toList($follow_up);
			$summary->save();

	}

	public function toList($items) {
			$list = "";
			$index = 1;
			foreach($items as $item) {
				$list = $list.$index.". ".$item;
				if ($index<sizeof($items)) {
						$list = $list."\n";
				}
				$index +=1;
			}

			return $list;
	}

}
