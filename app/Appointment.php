<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Appointment extends Model
{
	protected $table = 'appointments';
	protected $fillable = [
				'patient_id',
				'service_code',
				'appointment_slot'];
	
    protected $guarded = ['appointment_id'];
    protected $primaryKey = 'appointment_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'patient_id'=>'required',
				'service_code'=>'required',
				'appointment_slot'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}