<?php

namespace App;
use Carbon\Carbon;
use DateTime;
use Log;
use Excel;
use App\StockHelper;
use Config;

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

		public static function startOfDay($value)
		{
				if (!empty($value)) {
					$date = Carbon::parse($value)->startOfDay();
					return $date;
				} else {
					return null;
				}
		}

		public static function dayReadFormat($value)
		{
				if (!empty($value)) {
					return Carbon::parse($value)->format('d');
				} else {
					return null;
				}
		}

		public static function dayTimeReadFormat($value)
		{
				if (!empty($value)) {
					return Carbon::parse($value)->format('d/m, H:i');
				} else {
					return null;
				}
		}

		public static function militaryFormat($value)
		{
				if (!empty($value)) {
					return Carbon::parse($value)->format('d/m/Y H:i');
				} else {
					return null;
				}
		}

		public static function dateDayMonthFormat($value)
		{
				if (!empty($value)) {
					return Carbon::parse($value)->format('d/m');
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

		public static function dateYMDFormat($value)
		{
				if (!empty($value)) {
					return Carbon::parse($value)->format('Y/m/d');
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

		public static function dateYMDOnly($value)
		{
				if (!empty($value)) {
					if (strlen($value)>10) {
						return Carbon::parse($value)->format('Ymd');
					} else {
						return Carbon::parse($value)->format('Ymd');
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
					$value = str_replace("-","/", $value);
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


		public static function thisYear()
		{
				return (int)date('Y', strtotime(Carbon::now())); 
		}

		public static function thisDay()
		{
				return (int)date('d', strtotime(Carbon::now())); 
		}

		public static function thisMonth()
		{
				return (int)date('m', strtotime(Carbon::now())); 
		}

		public static function now()
		{
				return date('d/m/Y H:i', strtotime(Carbon::now())); 
		}

		public static function today()
		{
				return date('d/m/Y', strtotime(Carbon::now())); 
		}

		public static function todayYMD()
		{
				return date('Y/m/d', strtotime(Carbon::now())); 
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

		public static function addMinutes($value, $minutes)
		{
				$value = Carbon::createFromFormat('d/m/Y', $value);
				return $value->addMinutes($minutes);
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

		public static function diffInMinutes($date) 
		{
				return Carbon::createFromTimeStamp(strtotime($date))->diffInMinutes();
		}

		public static function diffInDays($date) 
		{
				return Carbon::createFromTimeStamp(strtotime($date))->diffInDays();
		}

		public static function diffInYears($date) 
		{
				return Carbon::createFromTimeStamp(strtotime($date))->diffInYears();
		}

		public static function diffInMinutesBetweenDates($dateStart, $dateEnd)
		{
				$end = Carbon::parse($dateEnd);
				$start = Carbon::parse($dateStart);
				$mins = $end->diffInMinutes($start);

				return $mins;
		}

		public static function diffInMonthsBetweenDates($dateStart, $dateEnd)
		{
				$end = Carbon::parse($dateEnd);
				$start = Carbon::parse($dateStart);
				$mins = $end->diffInMonths($start);

				return $mins;
		}

		public static function diffInDaysBetweenDates($dateStart, $dateEnd)
		{
				$end = Carbon::parse($dateEnd);
				$start = Carbon::parse($dateStart);
				$days = $end->diffInDays($start);

				return $days;
		}

		public static function dateIsBetween($dateStart, $dateEnd, $dateValue) 
		{
				$end = Carbon::parse($dateEnd);
				$start = Carbon::parse($dateStart);
				$value = Carbon::parse($dateValue);

				Log::info($start."-".$end." = ".$value);

				if ($value >= $start && $value <= $end) {
						return true;
				} else {
						return false;
				}
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
			return $mrn;
			/**
			$prefix = config('host.mrn_prefix');
			$prefix_length = strlen($prefix);
			if (is_null($mrn)) {
					return "-";
			} else {
					return substr($mrn,0,$prefix_length).'-'.substr($mrn,$prefix_length,8).'-'.substr($mrn,$prefix_length+8,4);
			}
			**/
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

		public static function roundUp10($value) {
				Log::info($value);
				$value = str_replace(",", "", $value);
				$valueInString = strval(round($value,2));
				if (strpos($valueInString, ".") == 0) $valueInString = $valueInString.".00";
				$valueArray = explode(".", $valueInString);
				$substringValue = substr($valueArray[1], 1);
				$substringValue = (int)$valueArray[1];
				if ($substringValue<10) { $substringValue = $substringValue*10; } 
				Log::info($valueArray);
				Log::info('----'.$substringValue);
				if ($substringValue >= 1 && $substringValue <= 50) {
						$tempValue = 0; //str_replace(substr($valueArray[1], 1), 5, substr($valueArray[1], 1));
						$tempValue = substr($valueArray[1],0,1).$tempValue;
						//$newvalue = floatval($valueArray[0].".".$tempValue);
						$newvalue = $valueArray[0];
				} elseif($substringValue == 0) {
						$newvalue = floatval($value);
				} else {
						//$newFloat = floatval($valueArray[0].".".substr($valueArray[1],0,1));
						//$newvalue = ($newFloat+0.1);
						$newvalue = $valueArray[0]+1;
				}
				 
				return $newvalue;

				//$newvalue = ceil($value/1)*1;
				//return number_format($newvalue,2);
		}

		public static function roundUp($value) {
				$value = str_replace(",", "", $value);
				$valueInString = strval(round($value,2));
				if (strpos($valueInString, ".") == 0) $valueInString = $valueInString.".00";
				$valueArray = explode(".", $valueInString);
				$substringValue = substr($valueArray[1], 1);
				 
				if ($substringValue >= 1 && $substringValue <= 4) {
						$tempValue = str_replace(substr($valueArray[1], 1), 0, substr($valueArray[1], 1));
						$tempValue = substr($valueArray[1],0,1).$tempValue;
						$newvalue = floatval($valueArray[0].".".$tempValue);
				} elseif ($substringValue == 5) {
						$newvalue = floatval($value);
				} elseif($substringValue == 0) {
						$newvalue = floatval($value);
				} else {
						$newFloat = floatval($valueArray[0].".".substr($valueArray[1],0,1));
						$newvalue = ($newFloat+0.1);
				}

				return $newvalue;	
				//return number_format($newvalue,2);
		}

		public static function round_five($num) {
				    return round($num*2,-1)/2;
		}

		public static function weekOfMonth($date) {
				$dt = Carbon::parse($date);
				return $dt->weekOfMonth;
		}

		public static function stockOnHand($product_code, $store_code = null, $batch_number = null)
		{
				$helper = new StockHelper();
				return $helper->getstockOnHand($product_code, $store_code, $batch_number);
		}

		public static function stringToKV($string)
		{
				//$string = "business_type,cafe|business_type_plural,cafes|sample_tag,couch|business_name,couch cafe";

				$finalArray = array();

				$asArr = explode( ';', $string );

				$finalArray['']='';
				foreach( $asArr as $val ){
				  $tmp = explode( '=>', $val );
				  if (count($tmp)>1) {
						  $finalArray[ $tmp[0] ] = $tmp[1];
				  } else {
						  $finalArray[ $val ] = $val;
				  }
				}

				return $finalArray;
		}

		public static function titleCase($str) {
			return ucwords(strtolower($str));
		}

		public static function openRequest($user, $document_code)
		{
			if ($user->author_id==18 or $user->author_id == 13 or $user->author_id==14) {
					$purchases = Purchase::where('document_code', $document_code)
							->orderBy('purchase_id', 'desc')
							->where('purchase_posted', 1)
							->whereNull('status_code');

					if ($user->author_id==18 or $user->author_id==13) {
						$purchases = $purchases->where('supplier_code', 'pmc_pharmacy');
					}

					if ($user->author_id==14) {
						$purchases = $purchases->where('supplier_code', 'pmc_purchase');
					}

					return $purchases->count();
			}

			return 0;
		}

		public static function multipleBill()
		{
				if (Config::get('host.multiple_bill')==1) {
						return true;	
				} else {
						return false;
				}
		}

}

