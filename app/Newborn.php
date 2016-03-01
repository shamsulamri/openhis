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
				'delivery_code',
				'newborn_weight',
				'newborn_length',
				'newborn_head_circumferance',
				'newborn_g6pd',
				'newborn_hepatitis_b',
				'newborn_bcg',
				'newborn_vitamin_k',
				'newborn_apgar',
				'newborn_term',
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
				'user_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}