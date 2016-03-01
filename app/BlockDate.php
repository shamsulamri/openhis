<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BlockDate extends Model
{
	protected $table = 'appointment_block_dates';
	protected $fillable = [
				'block_name',
				'block_date',
				'block_recur_annually',
				'block_recur_weekly',
				'block_recur_monthly'];
	
    protected $guarded = ['block_code'];
    protected $primaryKey = 'block_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'block_date'=>'size:10|date_format:d/m/Y',
			];

			
        	if ($method=='') {
        	    $rules['block_code'] = 'required|max:20.0|unique:appointment_block_dates';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setBlockDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['block_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getBlockDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

}