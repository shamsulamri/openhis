<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class OrderInvestigation extends Model
{
	protected $table = 'order_investigations';
	protected $fillable = [
				'order_id',
				'investigation_start',
				'urgency_code',
				'investigation_recur',
				'investigation_period',
				'period_code',
				'frequency_code'];
	

	public function validate($input, $method) {
			$rules = [
				'order_id'=>'required',
				'investigation_start'=>'size:10|date_format:d/m/Y',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setInvestigationStartAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['investigation_start'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getInvestigationStartAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

}