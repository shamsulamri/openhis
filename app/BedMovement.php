<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BedMovement extends Model
{
	protected $table = 'bed_movements';
	protected $fillable = [
				'admission_id',
				'encounter_id',
				'move_from',
				'move_to',
				'move_date'];
	
    protected $guarded = ['move_id'];
    protected $primaryKey = 'move_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'admission_id'=>'required',
				'move_date'=>'size:10|date_format:d/m/Y',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setMoveDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['move_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getMoveDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function bed() 
	{
			return $this->belongsTo('App\Bed', 'move_to','bed_code');
	}

}
