<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class BlockDate extends Model
{
	protected $table = 'appointment_block_dates';
	protected $fillable = [
				'block_name',
				'block_date',
				'block_date_end',
				'block_time_start',
				'block_time_end',
				'block_recur',
				'service_id',
		];
	
    protected $guarded = ['block_id'];
    protected $primaryKey = 'block_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
        	    'block_date'=>'required|date_format:d/m/Y',
        	    'block_date_end'=>'after:block_date|date_format:d/m/Y',
				'block_time_end'=>'required_with:block_time_start|date_format:H:i',
				'block_time_start'=>'date_format:H:i',
			];
			
        	if ($method=='') {
        	    $rules['block_code'] = 'max:20.0';
        	}
			
			$messages = [
				'required' => 'This field is required',
				'block_date_end.after' => 'Date end must be greater than date start.',
				'block_time_end.required_with' => 'Time end required.',
				'block_time_start.date_format' => 'Invalid time.',
				'block_time_end.date_format' => 'Invalid time.',
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setBlockDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['block_date'] = DojoUtility::dateWriteFormat($value);
		} else {
			$this->attributes['block_date'] = null;
		}
	}


	/**
	public function getBlockDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}
	**/

	public function getBlockDate() 
	{
		Log::info(gettype($this->attributes['block_date']));
		return $this->attributes['block_date'];
	}

	public function setBlockDateEndAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['block_date_end'] = DojoUtility::dateWriteFormat($value);
		} else {
			$this->attributes['block_date_end'] = null;
		}
	}

	public function getBlockDateEnd() 
	{
		return $this->attributes['block_date_end'];
	}

	public function service()
	{
			return $this->belongsTo('App\AppointmentService','service_id');
	}


	public function getBlockTimeStartAttribute($value)
	{
			return DojoUtility::timeReadFormat($value);
	}

	public function getBlockTimeEndAttribute($value)
	{
			return DojoUtility::timeReadFormat($value);
	}
}
