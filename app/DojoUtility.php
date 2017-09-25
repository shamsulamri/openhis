<?php

namespace App;
use Carbon\Carbon;
use DateTime;
use Log;
use Excel;

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
						return Carbon::parse($value)->format('d F Y, H:i');
					} else {
						return Carbon::parse($value)->format('d F Y');
					}
				} else {
					return null;
				}
		}

		public static function dateNoYearFormat($value)
		{
				if (!empty($value)) {
					if (strlen($value)>10) {
						return Carbon::parse($value)->format('d F, H:i');
					} else {
						return Carbon::parse($value)->format('d F');
					}
				} else {
					return null;
				}
		}

		public static function dateDMYOnly($value)
		{
				if (!empty($value)) {
					if (strlen($value)>10) {
						return Carbon::parse($value)->format('d F Y');
					} else {
						return Carbon::parse($value)->format('d F Y');
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

		public static function getAge($sourceDate)
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

		public static function formatMRN($mrn) 
		{
			if (is_null($mrn)) {
					return "-";
			} else {
					return substr($mrn,0,2).'-'.substr($mrn,2,8).'-'.substr($mrn,10,4);
			}
		}

		public static function dateDiff($date_1 , $date_2 , $differenceFormat = '%a' )
		{
				$date_1 = Carbon::parse($date_1)->format('Y/m/d');
				$date_2 = Carbon::parse($date_2)->format('Y/m/d');
				$date_1 = Carbon::parse($date_1);
				$date_2 = Carbon::parse($date_2);

				return $date_2->diffInDays($date_1);

		}

		public static function export_report($data)
		{
				//$data = $data->get();
				Log::info(count($data));
				$filename = "hms_".Carbon::now();
				Excel::create($filename, function($excel) use ($data) {

					$excel->sheet('Sheetname', function($sheet) use ($data) {
							$sheet->fromArray($data);
					});

				})->export('csv');
		}

		public static function roundUp($value) {
				$valueInString = strval(round($value,2));
				if (strpos($valueInString, ".") == 0) $valueInString = $valueInString.".00";
				$valueArray = explode(".", $valueInString);
				$substringValue = substr($valueArray[1], 1);
				 
				if ($substringValue >= 1 && $substringValue <= 5) {
						$tempValue = str_replace(substr($valueArray[1], 1), 5, substr($valueArray[1], 1));
						$tempValue = substr($valueArray[1],0,1).$tempValue;
						$newvalue = floatval($valueArray[0].".".$tempValue);
				} elseif($substringValue == 0) {
						$newvalue = floatval($value);
				} else {
						$newFloat = floatval($valueArray[0].".".substr($valueArray[1],0,1));
						$newvalue = ($newFloat+0.1);
				}
				 
				return $newvalue;
		}
}

