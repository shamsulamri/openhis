<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietComplain extends Model
{
	protected $table = 'diet_complains';
	protected $fillable = [
				'ward_code',
				'complain_date',
				'period_code',
				'meal_code',
				'contaminate_meal_other',
				'contamination_code',
				'complain_contaminate_other',
				'complain_other',
				'patient_mrn',
				'complain_reported',
				'complain_action'];
	
    protected $guarded = ['complain_id'];
    protected $primaryKey = 'complain_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'ward_code'=>'required',
				'complain_date'=>'size:10|date_format:d/m/Y|required',
				'period_code'=>'required',
				'meal_code'=>'required',
				'contamination_code'=>'required',
				'patient_mrn'=>'required',
			];

			
        	if ($method=='') {
        	    //$rules['complain_id'] = 'required|max:nan|unique:diet_complains';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function ward()
	{
			$this->belongsTo('App\Ward', ward_code);
	}
	
	public function setComplainDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['complain_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getComplainDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

}
