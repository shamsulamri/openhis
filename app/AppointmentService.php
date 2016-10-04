<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class AppointmentService extends Model
{
	protected $table = 'appointment_services';
	protected $fillable = [
				'service_name',
				'department_code',
				'service_start',
				'service_end',
				'service_duration',
				'service_monday',
				'service_tuesday',
				'service_wednesday',
				'service_thursday',
				'service_friday',
				'service_saturday',
				'service_sunday',
				'user_id',
				'service_block_dates',
				'service_status'];
	
    protected $guarded = ['service_id'];
    protected $primaryKey = 'service_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'department_code'=>'required',
				'service_status'=>'required',
				'service_start'=>'required',
				'service_end'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function getAppointments($service_id) 
	{
			$appointments = Appointment::where('service_id', $service_id)
					->select(['appointment_id','appointment_slot'])
					->get()
					->toArray();

			return $appointments;
	}

	public function getServiceStartAttribute($value)
	{
			return DojoUtility::timeReadFormat($value);
	}

	public function getServiceEndAttribute($value)
	{
			return DojoUtility::timeReadFormat($value);
	}
}
