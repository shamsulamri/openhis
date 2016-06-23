<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use App\Consultation;
use App\EncounterType;
use DB;
use Log;

class Encounter extends Model
{
	protected $table = 'encounters';
	protected $fillable = [
				'patient_id',
				'encounter_code',
				'type_code',
				'relation_code',
				'related_mrn',
				'triage_code',
				'employer_code',
				'employee_id',
				'organisation_code',
				'organisation_id',
				'triage_code',
		];
	
    protected $guarded = ['encounter_id'];
    protected $primaryKey = 'encounter_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'patient_id'=>'required',
				'encounter_code'=>'required',
			];

			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function patient()
	{
			return $this->belongsTo('App\Patient', 'patient_id');
	}
	
	public function admission()
	{
			return $this->hasOne('App\Admission', 'encounter_id');
	}

	public function consultation()
	{
			return $this->hasMany('App\Consultation', 'encounter_id');
	}

	public function discharge()
	{
			return $this->hasOne('App\Discharge','encounter_id');
	}

	public function bill()
	{
			return $this->hasOne('App\Bill', 'encounter_id');
	}

	public function queue()
	{
			return $this->hasOne('App\Queue', 'encounter_id');
	}

	public function encounterType() 
	{
			return $this->belongsTo('App\EncounterType', 'encounter_code');
	}

	public static function boot()
	{
			parent::boot();

			static::deleted(function($encounter)
			{
				$encounter->admission()->delete();
				$encounter->consultation()->delete();
			});
	}
	
	/*
	public function encounterPaid()
	{
			$amount = DB::table('bills as a')
						->where('encounter_id','=',$this->encounter_id)
						->count('bill_outstanding');

			return $amount;
	}
	 */
	public function encounterPaid() {

			$paid=False;
			$encounter = Encounter::where('patient_id', $this->patient_id)
							->orderBy('encounter_id','desc')
							->first();

			if ($encounter) {
					if ($encounter->bill) {
							$paid=True;
					}
			} 			
			

			return $paid;
	}


}
