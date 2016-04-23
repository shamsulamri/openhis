<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Dependant extends Model
{
	protected $table = 'patients';
	protected $fillable = [
				'patient_mrn',
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
				'patient_age',
				'patient_gravida',
				'patient_parity',
				'patient_parity_plus',
				'patient_lnmp'];
	
    protected $guarded = ['patient_id'];
    protected $primaryKey = 'patient_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'gender_code'=>'required',
				'patient_name'=>'required',
			];

			
			
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
