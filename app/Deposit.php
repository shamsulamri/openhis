<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Deposit extends Model
{
	protected $table = 'deposits';
	protected $fillable = [
				'encounter_id',
				'patient_id',
				'encounter_code',
				'deposit_amount',
				'payment_code',
				'deposit_description',
				'deposit_date',
				'user_id'];
	
    protected $guarded = ['deposit_id'];
    protected $primaryKey = 'deposit_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_code'=>'required',
				'deposit_amount'=>'required',
				'payment_code'=>'required',
			];
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function encounterType()
	{
			return $this->belongsTo('App\EncounterType','encounter_code');
	}

	public function patient()
	{
			return $this->belongsTo('App\Patient','patient_id');
	}

	public function encounter()
	{
			return $this->belongsTo('App\Encounter','encounter_id');
	}
	
	public function payment()
	{
			return $this->belongsTo('App\PaymentMethod','payment_code');
	}
}
