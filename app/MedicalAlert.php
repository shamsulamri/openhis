<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class MedicalAlert extends Model
{
	protected $table = 'medical_alerts';
	protected $fillable = [
				'patient_id',
				'consultation_id',
				'alert_description'];
	
    protected $guarded = ['alert_id'];
    protected $primaryKey = 'alert_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'patient_id'=>'required',
				'consultation_id'=>'required',
				'alert_description'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
