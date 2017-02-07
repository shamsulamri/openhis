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
					return Carbon::createFromFormat('d/m/Y', $value)->format('Y/m/d');
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

		public static function dateLongFormat($value)
		{
				if (!empty($value)) {
					if (strlen($value)>10) {
						return Carbon::parse($value)->format('d F Y H:i');
					} else {
						if (self::validateDate($value)==true) {
							return Carbon::createFromFormat('d/m/Y', $value)->format('d F Y');
						} else {
							return Carbon::parse($value)->format('d F Y');
						}
					}
				} else {
					return null;
				}
		}

		public static function dateOnlyFormat($value)
		{
				if (!empty($value)) {
					if (strlen($value)>10) {
						return Carbon::parse($value)->format('d F Y');
					} else {
						return Carbon::createFromFormat('d/m/Y', $value)->format('d F Y');
					}
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

		public static function addDays($value, $days)
		{
				$value = Carbon::createFromFormat('d/m/Y', $value);
				return $value->addDays($days);
		}

		public static function dateObject($value)
		{
				return date('Y-m-d', strtotime($value)); 
		}

		public static function dateFormat($format_from, $format_to,$value)
		{
				$value = Carbon::createFromFormat($format_from, $value);
				return date($format_to, strtotime($value)); 
		}

		public static function timenow()
		{
				return date('H:i', strtotime(Carbon::now())); 
		}

		public static function diffForHumans($date) 
		{
				return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
		}

		public static function removeTrailingZeros($value) 
		{
				return str_replace(".00","",$value);
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

