<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Newborn extends Model
{
	protected $table = 'enc_newborns';
	protected $fillable = [
				'encounter_id',
				'patient_id',
				'delivery_code',
				'newborn_weight',
				'newborn_length',
				'newborn_head_circumferance',
				'newborn_g6pd',
				'newborn_hepatitis_b',
				'newborn_bcg',
				'newborn_vitamin_k',
				'newborn_apgar',
				'newborn_thyroid',
				'apgar_heart_rate',
				'apgar_breathing',
				'apgar_grimace',
				'apgar_activity',
				'apgar_appearance',
				'newborn_gestational_weeks',
				'newborn_gestational_days',
				'complication_code',
				'birth_code',
				'user_id'];
	
    protected $guarded = ['newborn_id'];
    protected $primaryKey = 'newborn_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'delivery_code'=>'required',
				'newborn_weight'=>'required',
				'newborn_length'=>'required',
				'newborn_head_circumferance'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function encounter() 
	{
			return $this->belongsTo('App\Encounter','encounter_id');
	}

	public function patient()
	{
			return $this->belongsTo('App\Patient', 'patient_id');
	}

	public function deliveryMode()
	{
			return $this->belongsTo('App\DeliveryMode','delivery_code');
	}
	
	public function setNewbornG6pdAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['newborn_g6pd'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function getNewbornG6pdAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function setNewbornHepatitisBAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['newborn_hepatitis_b'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function getNewbornHepatitisBAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function setNewbornBcgAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['newborn_bcg'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function getNewbornBcgAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function setNewbornVitaminKAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['newborn_vitamin_k'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function getNewbornVitaminKAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function setNewbornThyroidAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['newborn_thyroid'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function getNewbornThyroidAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public static function boot()
	{
			parent::boot();

			static::updating(function($newborn)
			{
					$newborn->newborn_apgar = $newborn->apgar_heart_rate + $newborn->apgar_breathing + $newborn->apgar_grimace + $newborn->apgar_activity + $newborn->apgar_appearance; 
			});
	}
}
