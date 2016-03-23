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
				'discharge_diagnosis',
				'discharge_summary',
				'user_id'];
	
    protected $guarded = ['discharge_id'];
    protected $primaryKey = 'discharge_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'consultation_id'=>'required',
				'encounter_id'=>'required',
				'type_code'=>'required',
				'discharge_date'=>'size:10|date_format:d/m/Y',
				'user_id'=>'required',
			];

			
			
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

	public function getDischargeDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function consultation()
	{
			return $this->belongsTo('App\Consultation','consultation_id', 'consultation_id');
	}
}
