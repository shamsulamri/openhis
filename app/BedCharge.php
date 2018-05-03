<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BedCharge extends Model
{
	protected $table = 'bed_charges';
	protected $fillable = [
				'encounter_id',
				'bed_code',
				'bed_start',
				'block_room',
				'bed_stop'];
	
    protected $guarded = ['charge_id'];
    protected $primaryKey = 'charge_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'bed_code'=>'required',
				'bed_start'=>'size:10|date_format:d/m/Y',
				'bed_stop'=>'size:10|date_format:d/m/Y',
			];
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setBedStartAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['bed_start'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function setBedStopAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['bed_stop'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function bed() {
			return $this->belongsTo('App\Bed', 'bed_code');
	}

}
