<?php

namespace App;
use Carbon\Carbon;
use App\DietMenu;
use DB;
use App\BillMaterial;
use App\Admission;
use Log;

class DietHelper 
{
	public function menus($class, $period, $week, $day)
	{

			$menu_products = DietMenu::where('class_code',$class)
								->where('period_code', $period)
								->where('week_index', $week)
								->where('day_index', $day)
								->get();

			return $menu_products;			

	}

	public function order($diet, $class, $ward_code)
	{
			$dt = Carbon::now();
			$day = $dt->dayOfWeek;
			$week = $dt->weekOfMonth;

			if ($week>4) $week=1;

			$count = DB::table('diet_menus as a')
					->leftjoin('diet_classes as b', 'a.class_code','=','b.class_code')
					->where('b.diet_code', '=', $diet)
					->where('a.class_code','=', $class)
					->where('a.week_index','=', $week)
					->where('a.day_index','=', $day)
					->count();

			$patient_count=0;
			if ($count>0) {
				$patient_count = DB::table('admissions as a')
								->leftjoin('beds as b', 'b.bed_code','=', 'a.bed_code')
								->leftjoin('discharges as c', 'c.encounter_id','=', 'a.encounter_id')
								->where('a.diet_code','=', $diet)
								->where('a.class_code','=', $class)
								->where('ward_code','=', $ward_code)
								->where('a.nbm_status','=',0)
								->whereNull('discharge_id');
				Log::info($diet);
				Log::info($class);
				Log::info($ward_code);
				//dd($patient_count->toSql());

				$patient_count=$patient_count->count();
			}

			return $patient_count;

	}

	public function therapeutic_count($diet_code, $therapeutic_value, $ward_code) 
	{
			$count = DB::table('admissions as a')
						->leftjoin('beds as b', 'b.bed_code','=', 'a.bed_code')
						->leftjoin('discharges as c', 'c.encounter_id','=', 'a.encounter_id')
						->where('a.diet_code','=', $diet_code)
						->where('therapeutic_values', '=', $therapeutic_value)
						->where('ward_code','=', $ward_code)
						->where('a.nbm_status','=',0)
						->whereNull('discharge_id')
						->count();

			return $count;
	}

	public function diet($diet_code)
	{

			$patient_count = DB::table('admissions as a')
						->leftjoin('discharges as b', 'b.encounter_id','=', 'a.encounter_id')
						->where('diet_code', '=', $diet_code)
						->where('a.nbm_status','=',0)
						->whereNull('discharge_id')
						->count();

			return $patient_count;
	}

	public function nbm($diet_code, $ward_code)
	{

			$patient_count = DB::table('admissions as a')
						->leftjoin('beds as b', 'b.bed_code','=', 'a.bed_code')
						->leftjoin('discharges as c', 'c.encounter_id','=', 'a.encounter_id')
						->where('ward_code','=', $ward_code)
						->where('diet_code', '=', $diet_code)
						->where('a.nbm_status','=',1)
						->whereNull('discharge_id')
						->count();

			return $patient_count;
	}


	public function cooklist($diet, $class, $period, $product_code)
	{
			$dt = Carbon::now();
			$day = $dt->dayOfWeek;
			$week = $dt->weekOfMonth;

			if ($week>4) $week=1;

			$count = DB::table('diet_menus as a')
					->leftjoin('diet_classes as b', 'a.class_code','=','b.class_code')
					->where('b.diet_code', '=', $diet)
					->where('a.period_code','=', $period)
					->where('a.class_code','=', $class)
					->where('a.week_index','=', $week)
					->where('a.day_index','=', $day)
					->where('a.product_code','=', $product_code)
					->count();

			$patient_count=0;

			if ($count>0) {
				$patient_count = DB::table('admissions as a')
								->leftjoin('discharges as b', 'b.encounter_id','=', 'a.encounter_id')
								->where('diet_code','=', $diet)
								->where('class_code','=', $class)
								->where('a.nbm_status','=',0)
								->whereNull('discharge_id');

				//Log::info($diet);
				//Log::info($class);
				//dd($patient_count->toSql());

				$patient_count=$patient_count->count();
			}

			return $patient_count;

	}

	public function workorder($diet, $class, $period, $product_code, $ward_code)
	{
			$dt = Carbon::now();
			$day = $dt->dayOfWeek;
			$week = $dt->weekOfMonth;

			if ($week>4) $week=1;

			$count = DB::table('diet_menus as a')
					->leftjoin('diet_classes as b', 'a.class_code','=','b.class_code')
					->where('b.diet_code', '=', $diet)
					->where('a.period_code','=', $period)
					->where('a.class_code','=', $class)
					->where('a.week_index','=', $week)
					->where('a.day_index','=', $day)
					->where('a.product_code','=', $product_code)
					->count();

			$patient_count=0;
			if ($count>0) {
				$patient_count = DB::table('admissions as a')
								->leftjoin('discharges as b', 'b.encounter_id','=', 'a.encounter_id')
								->leftjoin('beds as c', 'c.bed_code', '=', 'a.bed_code')
								->where('a.diet_code','=', $diet)
								->where('a.class_code','=', $class)
								->where('ward_code', '=', $ward_code)
								->where('a.nbm_status','=',0)
								->whereNull('discharge_id')
								->count();
			}

			return $patient_count;

	}

	public function distribution($diet, $class, $period, $product_code, $ward_code)
	{
			$dt = Carbon::now();
			$day = $dt->dayOfWeek;
			$week = $dt->weekOfMonth;

			if ($week>4) $week=1;

			$count = DB::table('diet_menus as a')
					->leftjoin('diet_classes as b', 'a.class_code','=','b.class_code')
					->where('b.diet_code', '=', $diet)
					->where('a.period_code','=', $period)
					->where('a.class_code','=', $class)
					->where('a.week_index','=', $week)
					->where('a.day_index','=', $day)
					->where('a.product_code','=', $product_code)
					->count();

			$patient_count=0;
			if ($count>0) {
				$patient_count = DB::table('admissions as a')
								->leftjoin('beds as b', 'b.bed_code','=', 'a.bed_code')
								->leftjoin('discharges as c', 'c.encounter_id','=', 'a.encounter_id')
								->where('a.diet_code','=', $diet)
								->where('a.class_code','=', $class)
								->where('ward_code','=', $ward_code)
								->where('a.nbm_status','=',0)
								->whereNull('discharge_id');

				Log::info($diet);
				Log::info($class);
				Log::info($ward_code);
				//dd($patient_count->toSql());

				$patient_count=$patient_count->count();
			}

			return $patient_count;

	}

	public function bom($product_code)
	{
			$bom = BillMaterial::where('product_code',$product_code)->get();
			return $bom;	
	}

	public static function occupiedWards() 
	{
			$ward_codes = DB::table('admissions as a')
								->select('ward_code', DB::raw('count(*) as total'))
								->leftjoin('beds as b', 'b.bed_code','=', 'a.bed_code')
								->leftjoin('discharges as c', 'c.encounter_id','=', 'a.encounter_id')
								->where('a.nbm_status','=',0)
								->whereNull('discharge_id')
								->groupBy('ward_code')
								->pluck('ward_code');

			$wards = Ward::whereIn('ward_code', $ward_codes)->get();
			return $wards;
	}

	public static function updateNilByMouthStatus() 
	{

			/** Set nbm **/
			$sql = "select admission_id
					from admissions a
					left join ref_periods b on (a.period_code = b.period_code)
					where nbm_datetime is not null
					and now() between nbm_datetime and date_add(nbm_datetime, INTERVAL period_mins MINUTE)";

			$results = DB::select($sql);

			if (!empty($results)) {
					$nbms=array();
					foreach($results as $result) {
							Log::info($result->admission_id);
							array_push($nbms, $result->admission_id);
					}
					DB::table('admissions')->whereIn('admission_id', $nbms)->update(['nbm_status'=>1]);
					Log::info($nbms);
			}

			/** Remove nbm **/
			$sql = "select admission_id
					from admissions a
					left join ref_periods b on (a.period_code = b.period_code)
					where nbm_datetime is not null
					and now()>date_add(nbm_datetime, INTERVAL period_mins MINUTE)";

			$results = DB::select($sql);

			if (!empty($results)) {
					$nbms=array();
					foreach($results as $result) {
							Log::info($result->admission_id);
							array_push($nbms, $result->admission_id);
					}
					DB::table('admissions')->whereIn('admission_id', $nbms)->update(['nbm_status'=>0, 
							'nbm_duration'=>null,
							'nbm_datetime'=>null, 
							'period_code'=>null
					]);
					Log::info($nbms);
			}
	}

}

