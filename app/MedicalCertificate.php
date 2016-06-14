<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class MedicalCertificate extends Model
{
	protected $table = 'medical_certificates';
	protected $fillable = [
				'encounter_id',
				'consultation_id',
				'mc_start',
				'mc_end',
				'mc_identification',
				'mc_notes'];
	
    protected $guarded = ['mc_id'];
    protected $primaryKey = 'mc_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'consultation_id'=>'required',
				'mc_start'=>'required|size:10|date_format:d/m/Y',
				'mc_end'=>'size:10|date_format:d/m/Y|after:mc_start',
				'mc_identification'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setMcStartAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['mc_start'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function getMcStartAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function setMcEndAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['mc_end'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getMcEndAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function consultation()
	{
		return $this->hasOne('App\Consultation', 'consultation_id', 'consultation_id');
	}

}
