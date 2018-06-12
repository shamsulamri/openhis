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
							select a.encounter_id, a.bed_code from admissions as a
							left join ward_discharges b on (a.encounter_id = b.encounter_id)
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
	
	public function getEmptyRooms() {
			$sql = "
				select count(*) as allocations, a.room_code, occupants
				from beds as a
				left join (
				select count(*) as occupants, room_code
				from admissions as a
				left join encounters as b on (a.encounter_id = b.encounter_id)
				left join discharges as c on (c.encounter_id = b.encounter_id)
				left join ward_discharges as d on (d.discharge_id = c.discharge_id)
				left join beds as e on (e.bed_code = a.bed_code)
				where d.discharge_id is null
				group by room_code
				) as b on (b.room_code = a.room_code) 
				where encounter_code = 'inpatient'
				and occupants is null
				group by a.room_code, occupants";
			
			$results = DB::select($sql);

			if (!empty($results)) {
				return $results;
			} else {
				return null;
			}
	}

	public function bedOccupancyRate($department=null, $year=null, $month=null)
	{

			$subquery = "
						select IF(DATEDIFF(b.created_at,a.created_at)>0,DATEDIFF(b.created_at,a.created_at),1) as days
						from encounters as a
						left join discharges as b on (b.encounter_id = a.encounter_id)
						left join admissions as d on (d.encounter_id = a.encounter_id)
						left join beds as e on (e.bed_code = d.bed_code)
						left join wards as f on (f.ward_code = e.ward_code)
						where a.encounter_code = 'inpatient'
						and discharge_id is not null
			";

			$subquery2 = "
						select count(*) as beds from beds where encounter_code = 'inpatient'
			";

			if ($department) {
					$subquery = $subquery . " and f.department_code='".$department."'";
					$subquery2 = $subquery2 . " and department_code='".$department."'";
			}
			if ($year) $subquery = $subquery . " and year(b.created_at)=".$year;
			if ($month) $subquery = $subquery . " and month(b.created_at)=".$month;


			$sql = "
				select (sum(days)*100)/(beds*365) as bor
				from (". $subquery . ") as a
				cross join (". $subquery2 .") as b
				group by beds";

			$results = DB::select($sql);

			Log::info($sql);
			if (!empty($results)) {
				return number_format($results[0]->bor,2);
			} else {
				return 0;
			}
	}

	public function totalBed($ward_code=null, $class_code=null) 
	{
			$beds = Bed::all();

			if ($ward_code) {
				$beds = $beds->where('ward_code', $ward_code);
			}

			if ($class_code) {
				$beds = $beds->where('class_code', $class_code);
			}

			return $beds->count();
	}

	public function bedAvailable($ward_code=null, $class_code=null) {

			$beds = Admission::leftJoin('ward_discharges as b', 'admissions.encounter_id', '=', 'b.encounter_id')
						->leftJoin('beds as c', 'c.bed_code', '=', 'admissions.bed_code');

			if ($ward_code) {
				$beds = $beds->where('ward_code', $ward_code);
			}

			if ($class_code) {
				$beds = $beds->where('c.class_code', $class_code);
			}

			$beds = $beds->whereNull('discharge_id');

			return $this->totalBed($ward_code, $class_code)-$beds->count();


	
	}

	public function dischargeClinical($ward_code=null, $class_code=null) {

			$sql = "select count(*) as total from admissions as a
					left join ward_discharges b on (a.encounter_id = b.encounter_id)
					left join discharges c on (a.encounter_id = c.encounter_id)
					where c.discharge_id is not null
					and b.discharge_id is null
					";

			$beds = Admission::leftJoin('ward_discharges as b', 'admissions.encounter_id', '=', 'b.encounter_id')
						->leftJoin('discharges as c', 'c.encounter_id','=', 'admissions.encounter_id')
						->leftJoin('beds as d', 'd.bed_code', '=', 'admissions.bed_code')
						->whereNotNull('c.discharge_id')
						->whereNull('b.discharge_id');

			if ($ward_code) {
				$beds = $beds->where('ward_code', $ward_code);
			}

			if ($class_code) {
				$beds = $beds->where('d.class_code', $class_code);
			}

			return $beds->count();
	
	
	}
}
