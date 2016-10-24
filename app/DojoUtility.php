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

		public static function today()
		{
				return date('d/m/Y', strtotime(Carbon::now())); 
		}

		public static function tomorrow()
		{
				$dt = Carbon::now();
				return date('d/m/Y', strtotime($dt->addDay())); 
		}

		public static function timenow()
		{
				return date('H:i', strtotime(Carbon::now())); 
		}

		public static function diffForHumans($date) 
		{
				return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
		}

		public function getAge($sourceDate)
		{
				$value = "";
				if ($sourceDate) {
					$birthdate = DateTime::createFromFormat('Y-m-d', $sourceDate);
					$today = new DateTime(); 
					$diff = $today->diff($birthdate);
					if ($diff->y>0) {
						if ($diff->y>2) {
							$value = $diff->y." year old";
						} else {
							$value = $diff->y*12+$diff->m." month old";
						}
					}
					if ($diff->y==0) {
							if ($diff->m==0) {
								$value = $diff->d." day old";
							} else {
								$value = $diff->m." month old";
							}
					}

				} else {
						$value = "-";
				}

				return $value;
		}
}

