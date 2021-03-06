<?php

namespace App;
use DB;
use Carbon\Carbon;
use App\Encounter;
use Log;
use App\Consultation;

class EncounterHelper 
{
		public static function getConsultationDate($id)
		{
				$consultation = Consultation::find($id);
				return $consultation->created_at;
		}

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

		public static function getCurrentAdmission($encounter_id)
		{
				$admission = Admission::where('encounter_id',$encounter_id)
							->orderBy('encounter_id','desc')
							->first();

				return $admission;

		}

		public static function getConsultation($id)
		{
			$consultation = Consultation::find($id);
			return $consultation;
		}

		public static function getLastConsultation($patient_id)
		{
				$consultation = Consultation::where('patient_id', $patient_id)
						->orderBy('consultation_id', 'desc')
						->first();
			
				return $consultation;
		}


		public static function getLastEncounter($patient_id)
		{
				$encounter = Encounter::where('patient_id', $patient_id)
						->leftjoin('discharges as b', 'b.encounter_id', '=', 'encounters.encounter_id')
						->orderBy('encounters.encounter_id', 'desc')
						->whereNotNull('discharge_id')
						->first();

				return $encounter;

		}

}

?>
