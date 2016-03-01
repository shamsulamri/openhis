<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

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
				'organisation_id'
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

	
}
