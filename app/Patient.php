<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
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
				'patient_name',
				'patient_birthdate',
				'patient_new_ic',
				'patient_old_ic',
				'patient_passport',
				'patient_splp',
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
				'patient_is_pati',
				'patient_birthtime',
				'patient_is_royal',
				'patient_is_vip',
				'patient_is_unknown',
				'patient_related_mrn',
				'relation_code',
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

	public function validate($input, $tab) {
			$rules = [];
			
			switch ($tab) {
				case "demography":
					$rules = [
						'gender_code'=>'required',
						'patient_name'=>'required',
						'patient_birthdate'=>'date_format:d/m/Y',
					];
					if (empty($this->attributes['patient_is_unknown']) == false) {
							if ($this->attributes['patient_is_unknown'] == 1) {
									$rules['patient_age']='required';
							}
					}
					break;
				default:
						$rules = ['patient_email'=>'email'];
			}						

			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
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


}
