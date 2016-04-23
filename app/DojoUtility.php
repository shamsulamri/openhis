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

		public static function validateDateTime($date)
		{
				$d = DateTime::createFromFormat('d/m/Y H:i', $date);
				return $d && $d->format('d/m/Y H:i') == $date;
		}
		
		public static function dateTimeWriteFormat($value)
		{
				if (!empty($value)) { 
					return Carbon::createFromFormat('d/m/Y H:i', $value);
				} else {
					return null;
				}	
		}
		
		public static function dateTimeReadFormat($value)
		{
				if (!empty($value)) {
					return Carbon::parse($value)->format('d/m/Y H:i');
				} else {
					return null;
				}
		}
		
		public static function timeReadFormat($value)
		{
				if (!empty($value)) {
					return Carbon::parse($value)->format('H:i');
				} else {
					return null;
				}
		}
		
		public static function logout($log)
		{
				Log::info($log);
		}

		public static function now()
		{
				return date('d/m/Y H:i', strtotime(Carbon::now())); 
		}

}

