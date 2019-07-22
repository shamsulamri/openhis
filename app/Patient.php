<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use DB;
use DateTime;
use Log;

class Patient extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'patients';
	protected $fillable = [
				'tourist_code',
				'gender_code',
				'religion_code',
				'title_code',
				'marital_code',
				'nation_code',
				'race_code',
				'occupation_code',
				'flag_code',
				'patient_name',
				'patient_birthdate',
				'patient_new_ic',
				'patient_old_ic',
				'patient_passport',
				'patient_birth_certificate',
				'patient_cur_street_1',
				'patient_cur_street_2',
				'patient_cur_street_3',
				'patient_cur_city',
				'patient_cur_postcode',
				'patient_cur_state',
				'patient_cur_country',
				'patient_phone_home',
				'patient_phone_mobile',
				'patient_phone_office',
				'patient_phone_fax',
				'patient_email',
				'patient_per_street_1',
				'patient_per_street_2',
				'patient_per_street_3',
				'patient_per_city',
				'patient_per_postcode',
				'patient_per_state',
				'patient_per_country',
				'patient_work_company',
				'patient_work_rn',
				'patient_work_street_1',
				'patient_work_street_2',
				'patient_work_city',
				'patient_work_postcode',
				'patient_work_state',
				'patient_work_country',
				'patient_work_number',
				'patient_work_person',
				'patient_police_id',
				'patient_military_id',
				'patient_birthtime',
				'patient_is_unknown',
				'patient_gravida',
				'patient_parity',
				'patient_parity_plus',
				'patient_lnmp',
				'patient_edd',
				'patient_block',
				'patient_age'];
	
	protected $defaults = [
			'patient_cur_country'=>'MYS',
	];
   
	protected $guarded = ['patient_id'];
    protected $primaryKey = 'patient_id';
    public $incrementing = true;
    
	public function __construct(array $attributes = array())
	{
			    $this->setRawAttributes($this->defaults, true);
				    parent::__construct($attributes);
	}

	public function getCurrentAddress() 
	{
		$value = "";
		if (!empty($this->patient_cur_street_1)) $value = $value.$this->patient_cur_street_1.", ";
		if (!empty($this->patient_cur_street_2)) $value = $value.$this->patient_cur_street_2.", ";
		if (!empty($this->patient_cur_street_3)) $value = $value.$this->patient_cur_street_3.", ";
		if (!empty($this->patient_cur_postcode)) {
			$value = $value.' '.$this->patient_cur_postcode;

			$postcode = Postcode::find($this->patient_cur_postcode);
			if (!empty($postcode)) {
				$value = $value.' '.$postcode->city->city_name;
				$value = $value.', '.$postcode->state->state_name;
			}
		}

		return $value;
	}

	public function validate($input, $method) {
			$rules = [];
			
			$rules = [
				'gender_code'=>'required',
				'patient_name'=>'required',
				'patient_birthdate'=>'date_format:d/m/Y',
				'patient_email'=>'email',
			];

        	if ($method=='') {
        	    $rules['patient_new_ic'] = 'unique:patients';
        	    $rules['patient_old_ic'] = 'unique:patients';
        	    $rules['patient_passport'] = 'unique:patients';
        	    $rules['patient_birth_certificate'] = 'unique:patients';
        	    $rules['patient_police_id'] = 'unique:patients';
        	    $rules['patient_military_id'] = 'unique:patients';
			}

			if (empty($this->attributes['patient_is_unknown']) == false) {
					if ($this->attributes['patient_is_unknown'] == 1) {
							$rules['patient_age']='required';
					}
			}

			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function getPatientNameAttribute($value)
	{
			return strtoupper($value);
	}

	public function setPatientNameAttribute($value) 
	{
			$this->attributes['patient_name'] = strtoupper($value);
	}

	public function setPatientBirthdateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['patient_birthdate'] = DojoUtility::dateWriteFormat($value);
		}
	}


	/**
	public function getPatientBirthdateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}
	**/

	public function getPatientAgeAttribute($value)
	{
		if ($value==0) {
				return "";
		} else {
				return $value;
		}
	}

	public function setPatientLnmpAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['patient_lnmp'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function setPatientEddAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['patient_edd'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function getPatientEddAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function getPatientLnmpAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	/**
	public function getPatientMrnAttribute($value)
	{
			if (is_null($value)) {
					return "-";
			} else {
					return $value;
			}
	}
	**/

	public function getMRN()
	{
			if (!$this->attributes['patient_mrn']) {
					return "-";
			} else {
					return DojoUtility::formatMRN($this->attributes['patient_mrn']);
			}
	}

	public function getPatientBirthtimeAttribute($value)
	{
			return DojoUtility::timeReadFormat($value);
	}

	public function patientIdentification()
	{
			if (!empty($this->attributes['patient_new_ic'])) {
					return $this->patient_new_ic;
			} else {
					return "-";
			}
			return "-";
	}
					
	public function encounters()
	{
			return $this->hasMany('App\Encounter', 'patient_id');
	}

	public function race()
	{
			return $this->belongsTo('App\Race', 'race_code');
	}

	public function gender()
	{
			return $this->belongsTo('App\Gender', 'gender_code');
	}

	public function title()
	{
			return $this->belongsTo('App\Title', 'title_code');
	}

	public function getTitle()
	{
			if (!empty($this->title_code)) {
					return strtoupper($this->title->title_name);
			} else {
					return "";
			}
	}

	public function getTitleName()
	{
		if ($this->title) {
				Log::info($this->title->title_name);
			return strtoupper($this->title->title_name.' '.$this->patient_name);
		} else {
			return strtoupper($this->patient_name); 
		}
	}

	public function alert()
	{
			$alerts =  $this->hasMany('App\MedicalAlert', 'patient_id', 'patient_id');
			$alerts = $alerts->where('alert_public','=',1);
			return $alerts;
	}

	public function outstandingBill()
	{
			return 0;
			$amount = DB::table('bills as a')
						->leftjoin('encounters as b', 'a.encounter_id','=', 'b.encounter_id')
						->where('b.patient_id','=',$this->patient_id)
						->sum('bill_outstanding');

			$sql = "select (sum(IFNULL(bill_payment_total,0)-IFNULL(bill_change,0)-bill_total) + IFNULL(nonenc_payment,0)) as outstanding
						from bills as a
						left join encounters b on (a.encounter_id=b.encounter_id)
						left join (
							select patient_id, sum(payment_amount) as nonenc_payment from payments 
							where encounter_id=0
							group by patient_id
						) as c on (c.patient_id = b.patient_id)
						where b.patient_id=".$this->patient_id." group by b.patient_id";

			$amount = DB::select($sql);

			$value = 0;

			if ($amount) $value = $amount[0]->outstanding;

			$deposit_total = DB::table('deposits as a')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->where('patient_id','=', $this->patient_id)
					->sum('deposit_amount');

			$others=0;
			/**
			$others = DB::table('bill_items as a')
					->leftjoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->where('patient_id','=', $this->patient_id)
					->where('product_code','!=', 'others')
					->sum('bill_total');
			**/

			$value = $value + $deposit_total+$others;
			Log::info($value);
			return $value;
	}


	public function activeEncounter() 
	{
			$encounter_active=True;
			$encounter_completed=True;
			$encounter = Encounter::where('patient_id', $this->patient_id)
							->orderBy('encounter_id','desc')
							->first();

			if (empty($encounter->bill)) {
					return $encounter;
			} else {
					return null;
			}
	}

	/*
			if (!empty($encounter)) {
					switch ($encounter->encounter_code) {
						case 'inpatient':
							if (empty($encounter->admission->bed)) $encounter_completed=False;
							break;
						case 'mortuary':
							if (empty($encounter->admission->bed)) $encounter_completed=False;
							break;
						case 'daycare':
							if (empty($encounter->admission->bed)) $encounter_completed=False;
							break;
						case 'emergency':
							if ($encounter->triage_code=='green' && $encounter->queue==null) $encounter_completed=False;
							if ($encounter->triage_code<>'green' && empty($encounter->admission->bed)) $encounter_completed=False;
							break;
						case 'outpatient':
							if ($encounter->queue==null) $encounter_completed=False;
							break;
					}
					if (!$encounter_completed) {
							Encounter::find($encounter->encounter_id)->delete();
							$encounter_active=False;
					}
					if ($encounter->discharge) {
							if ($encounter->bill) {
									$encounter_active=False;
							}
					}
			} else {
				$encounter_active=False;
			}
			
			if ($encounter_active) {
					return $encounter;
			} else { 
					return null;
			}
	}
	 */

	public function getCurrentEncounterModel() 
	{
			$encounter = Encounter::where('patient_id', $this->patient_id)
							->orderBy('encounter_id','desc')
							->first();
			
			if ($encounter) {
					if (!$encounter->discharge) {
							return $encounter;
					} else {
							return null;
					}
			} else {
					return null;
			}

	}

	public function getCurrentEncounter()
	{
			$status = "";
			$encounter = Encounter::where('patient_id', $this->patient_id)
							->orderBy('encounter_id','desc')
							->first();
			
			if ($encounter) {
					if ($encounter->discharge) { 
							if ($encounter->sponsor_code) {
									$claimable_amount = BillItem::where('encounter_id', '=', $encounter->encounter_id)
															->where('bill_non_claimable', '=', 0)
															->sum('bill_amount');

									$non_claimable_amount = BillItem::where('encounter_id', '=', $encounter->encounter_id)
															->where('bill_non_claimable', '=', 1)
															->sum('bill_amount');

									$bill_complete = false;
									if ($claimable_amount>0) {
											$claimable_bill = Bill::where('encounter_id', '=', $encounter->encounter_id)
														->where('bill_non_claimable', '=', 0)
														->first();
									}

									if ($non_claimable_amount>0) {
											$non_claimable_bill = Bill::where('encounter_id', '=', $encounter->encounter_id)
														->where('bill_non_claimable', '=', 1)
														->first();
									}

									if ($claimable_amount>0 && $non_claimable_amount>0) {
											Log::info("-----");
											if ($claimable_bill && $non_claimable_bill) {
												$bill_complete = true;
											}
									} else {
											if ($claimable_amount>0 || $non_claimable_amount>0) {
													if (!empty($claimable_bill) || !empty($non_claimable_bill)) {
														$bill_complete = true;
													}
											}
									}


									Log::info("X: ".$claimable_amount);
									Log::info("X: ".$non_claimable_amount);

									if (!$bill_complete) {
											$status =  "Billing process...";
									}

							} else {
									if (empty($encounter->bill)) {
											$status =  "Billing process...";
									}
							}
							$status = "<span class='label label-primary'>".$status."</span>";
							if (empty($encounter->bill)) {
								$status.=' ('.$encounter->encounter_description.')';
							}
					} else {
							if ($encounter->admission) {
									$status =  $encounter->admission->bed->bed_name." (".$encounter->admission->bed->ward->ward_name.")";
							} else {
									if ($encounter->queue) {
										$status =  $encounter->queue->location->location_name;
									}
							}
							$status = "<span class='label label-primary'>".$status."</span>";
					}

					return $status;
			} else {
				return null;
			}
	}

	public function getDischargeOrders()
   	{
			$discharge_orders = Order::select('orders.order_id')
									->leftjoin('order_cancellations as b','orders.order_id','=', 'b.order_id')
									->leftjoin('encounters as c','c.encounter_id','=', 'orders.encounter_id')
									->leftjoin('patients as d','d.patient_id','=', 'c.patient_id')
									->leftjoin('order_investigations as e', 'orders.order_id','=','e.order_id')
									->where('investigation_date', '>=', Carbon::today())
									->where('order_completed','=',0)
									->where('order_is_discharge','=',1)
									->where('c.patient_id','=', $this->patient_id)
									->whereNull('cancel_id')
									->get();

			return $discharge_orders;

	}


	public static function boot()
	{
			parent::boot();

			static::created(function($patient)
			{
					//$prefix = config('host.mrn_prefix') . date('Ymd', strtotime(Carbon::now()));
					//$mrn = $prefix.str_pad(Patient::where('created_at','>=', Carbon::today())->count(), 4, '0', STR_PAD_LEFT);
					//$patient->patient_mrn = $mrn;
					//$patient->save();
			});
	}

	public function patientAge()
	{
			$value = DojoUtility::getAge($this->patient_birthdate);
			if ($value != '-') {
				$value = $value.', '.$this->gender->gender_name;
			}
			return $value;
	}

	public function patientAgeNumber()
	{
			$value = DojoUtility::getAge($this->patient_birthdate);
			return $value;
	}

	public function patientAgeInDays()
	{
			$value = DojoUtility::diffInDays($this->patient_birthdate);
			return $value;
	}

	public function patientAgeInYears()
	{
			$value = DojoUtility::diffInYears($this->patient_birthdate);
			return $value;
	}
}
