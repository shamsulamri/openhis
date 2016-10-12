
<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use Log;

class LoanHelper 
{
	
	public function totalAdmission()
	{

			$sql = "select count(*) as total_admission from admissions a
					left join beds b on (a.bed_code = b.bed_code)
					left join discharges c on (c.encounter_id = a.encounter_id)
					where ward_code = '".$this->ward_code."'
					and discharge_id is null";

			$results = DB::select($sql);
			
			return $results[0]->total_admission;

	}

}
