<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Encounter;
use App\Consultation;
use Log;

class WardHelper 
{
	
	public $ward_code;
	public $openConsultationId=0;    

	function __construct($code="") {
		$this->ward_code = $code;
	}

	public function totalBed() 
	{
			$beds = Bed::where('ward_code', $this->ward_code)
						->count();
			return $beds;
	}

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

	public function todayAdmissions()
	{
			$sql = "select count(*) as total_admission from admissions a
					left join beds b on (a.bed_code = b.bed_code)
					left join discharges c on (c.encounter_id = a.encounter_id)
					where a.created_at >= '".DojoUtility::dateWriteFormat(DojoUtility::today())."'
					and discharge_id is null";

			$results = DB::select($sql);
			
			return $results[0]->total_admission;

	}

	public function bedAvailable() {

			$sql = "select count(*) as bed_available from admissions as a
					left join ward_discharges b on (a.encounter_id = b.encounter_id)
					left join beds c on (c.bed_code = a.bed_code)
					where discharge_id is null
					and ward_code = '".$this->ward_code."'";

			$results = DB::select($sql);

			if (!empty($results)) {
				$beds = Bed::where('ward_code', $this->ward_code)
						->count();
				return $beds-$results[0]->bed_available;
			} else {
				return 0;
			}

	
	}

	public function wardDischarge() {

			$sql = "select count(*) as total from admissions as a
					left join ward_discharges b on (a.encounter_id = b.encounter_id)
					left join discharges c on (a.encounter_id = c.encounter_id)
					left join beds d on (d.bed_code = a.bed_code)
					where c.discharge_id is not null
					and b.discharge_id is null";

			if ($this->ward_code) {
					$sql = $sql." and ward_code='".$this->ward_code."'";
			}

			$results = DB::select($sql);

			if (!empty($results)) {
				return $results[0]->total;
			} else {
				return 0;
			}

	
	}

	public function hasOpenConsultation($patientId, $encounter_id, $user_id)
	{
			$consultation = Consultation::where('patient_id','=',$patientId)
					->where('encounter_id',$encounter_id)
					->where('consultation_status',1)
					->where('user_id',$user_id)
					->get();
			
		    if (count($consultation)>0) {	
					$this->openConsultationId=$consultation[0]->consultation_id;
					return $consultation[0];
			} else {
					return;
			}
	}

	public function getOpenConsultation($consultation_id) {
			$consultation = Consultation::find($consultation_id)->get();
			return $consultation;

	}

}
