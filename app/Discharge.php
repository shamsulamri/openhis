<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Discharge extends Model
{
	protected $table = 'discharges';
	protected $fillable = [
				'consultation_id',
				'encounter_id',
				'type_code',
				'discharge_date',
				'discharge_time',
				'discharge_diagnosis',
				'discharge_summary',
				'user_id'];
	
    protected $guarded = ['discharge_id'];
    protected $primaryKey = 'discharge_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'discharge_date'=>'size:10|date_format:d/m/Y',
			];
			
        	if ($method=='') {
				$rules['consultation_id'] = 'required';
				$rules['encounter_id'] = 'required';
				$rules['type_code'] = 'required';
				$rules['user_id'] = 'required';
			}

			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setDischargeDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['discharge_date'] = DojoUtility::dateWriteFormat($value);
		}
	}

	/**
	public function getDischargeDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}
	**/

	public function consultation()
	{
			return $this->belongsTo('App\Consultation','consultation_id', 'consultation_id');
	}

	public function encounter() 
	{
			return $this->belongsto('App\Encounter', 'encounter_id');
	}

	public function medical_certificate() 
	{
			return $this->hasOne('App\MedicalCertificate', 'encounter_id');
	}

	public function getTableColumns() {
			return "X";
	}
}
