<?php

namespace App;
use DB;
use Carbon\Carbon;
use App\Encounter;
use Log;

class FormHelper
{
		public static function lastUpdate($patient_id, $form_code)
		{

			$sql = sprintf("
				select updated_at
				from form_values a
				where patient_id= %d
				and form_code = '%s'
				order by updated_at desc 
				limit 1
				", $patient_id, $form_code);

			$results = DB::select($sql);

			if (!empty($results)) {
				return $results[0]->updated_at;
			} else {
				return null;
			}
		}
}

?>
