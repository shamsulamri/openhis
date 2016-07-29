<?php

namespace App;
use DB;
use Carbon\Carbon;
use App\Encounter;
use Log;

class EncounterHelper 
{
		public static function encounterComplete($id)
		{
			$encounter_complete=False;
			$encounter = Encounter::find($id);
			if ($encounter) {
					if ($encounter->admission) {
							if ($encounter->admission->bed) $encounter_complete=True;
					}
					if ($encounter->queue) $encounter_complete=True;
			}
			return $encounter_complete;
		}

		public static function getActiveEncounter($patient_id) 
		{
			$encounter = Encounter::where('patient_id', $patient_id)
							->orderBy('encounter_id','desc')
							->first();
			
			if ($encounter) {
					if (!empty($encounter->discharge)) $encounter=null;
			}

			return $encounter;
		}
}

?>
