<?php

namespace App;
use Carbon\Carbon;
use DateTime;
use Log;

class DojoUtility 
{
		public static function dateWriteFormat($value)
		{
				if (!empty($value)) { 
					return Carbon::createFromFormat('d/m/Y', $value);
				} else {
					return null;
				}	
		}

		public static function dateReadFormat($value)
		{
				if (!empty($value)) {
					return Carbon::parse($value)->format('d/m/Y');
				} else {
					return null;
				}
		}

		public static function validateDate($date)
		{
				$d = DateTime::createFromFormat('d/m/Y', $date);
				return $d && $d->format('d/m/Y') == $date;
		}

		public static function logout($log)
		{
				Log::info($log);
		}
}

