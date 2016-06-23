<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use DB;
use Log;

class Patient extends Model
{
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
				'registration_code',
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
				'patient_police_id',
				'patient_military_id',
				'patient_birthtime',
				'patient_is_unknown',
				'patient_gravida',
				'patient_parity',
				'patient_parity_plus',
				'patient_lnmp',
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

	public function validate($input) {
			$rules = [];
			
			$rules = [
				'gender_code'=>'required',
				'patient_name'=>'required',
				'patient_birthdate'=>'date_format:d/m/Y',
				'patient_email'=>'email',
			];
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


	public function getPatientBirthdateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

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


	public function getPatientLnmpAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function getPatientMrnAttribute($value)
	{
			if (is_null($value)) {
					return "-";
			} else {
					return $value;
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
					return $this->title->title_name;
			} else {
					return "";
			}
	}

	public function alert()
	{
			return $this->hasMany('App\MedicalAlert', 'patient_id', 'patient_id');
	}

	public function outstandingBill()
	{
			$amount = DB::table('bills as a')
						->leftjoin('encounters as b', 'a.encounter_id','=', 'b.encounter_id')
						->where('b.patient_id','=',$this->patient_id)
						->sum('bill_outstanding');

			$sql = "select (sum(bill_payment_total-bill_change-bill_grand_total) + IFNULL(nonenc_payment,0)) as outstanding
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
			return $value;
	}


	public function hasActiveEncounter() 
	{
			$encounter_active=True;
			$encounter_completed=True;
			$encounter = Encounter::where('patient_id', $this->patient_id)
							->orderBy('encounter_id')
							->first();

			if ($encounter) {
					if ($encounter->admission==null && $encounter->queue==null) $encounter_completed=False;
					if ($encounter->discharge) {
							$encounter_active=False;
					}
			} else {
				$encounter_active=False;
			}

			if (!$encounter_completed) {
					Encounter::find($encounter->encounter_id)->delete();
					$encounter_active=False;
			}
			
			return $encounter_active;
	}

}
