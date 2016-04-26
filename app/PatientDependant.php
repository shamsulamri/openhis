<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PatientDependant extends Model
{
	protected $table = 'patient_dependants';
	protected $fillable = [
				'patient_id',
				'dependant_id',
				'relation_code'];
	

	public function validate($input, $method) {
			$rules = [
				'patient_id'=>'required',
				'dependant_id'=>'required',
				'relation_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function patient()
	{
			return $this->belongsTo('App\Patient', 'dependant_id');
	}

	
}
