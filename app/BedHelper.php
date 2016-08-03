<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use Log;

class BedHelper 
{
	public function occupiedBy($bed_code, $ward_code) {

			$sql = "select encounter_id
					from beds a
					left join (
							select a.encounter_id, bed_code from admissions as a
							left join discharges b on (a.encounter_id = b.encounter_id)
							where discharge_id is null
					) as b on (a.bed_code = b.bed_code)
					where a.bed_code = '".$bed_code."'
					and a.ward_code = '".$ward_code."'";

			$results = DB::select($sql);

			if (!empty($results)) {
				$encounter_id = $results[0]->encounter_id;
				$encounter = Encounter::find($encounter_id);
				if ($encounter) {
					$patient = $encounter->patient;
					return $patient->patient_name.' ('.$patient->patient_mrn.')';
				} else {
					return "-";
				}
			} else {
				return 0;
			}

	
	}
	

}
