<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use App\Consultation;
use Log;
use DB;

class Admission extends Model
{
	protected $table = 'admissions';
	protected $fillable = [
				'encounter_id',
				'user_id',
				'bed_code',
				'admission_code',
				'referral_code',
				'employer_code',
				'employee_id',
				'organisation_code',
				'organisation_id',
				'texture_code',
				'class_code',
				'admission_nbm'];
	
    protected $guarded = ['admission_id'];
    protected $primaryKey = 'admission_id';
    public $incrementing = true;
	public $openConsultationId=0;    

	public function validate($input, $method) {
			$rules=[];
			switch ($method) {
			case "PUT":
					$rules = [
						'encounter_id'=>'required',
						'user_id'=>'required',
					];
					break;
			default:
					$rules=[];
			}
			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function encounter() 
	{
			return $this->belongsTo('App\Encounter', 'encounter_id');
	}

	public function bed()
	{
			return $this->hasOne('App\Bed', 'bed_code', 'bed_code');
	}

	public function hasOpenConsultation($patientId)
	{
			$consultation = Consultation::where('patient_id','=',$patientId)
					->where('consultation_status',2)
					->get();
			
		    if (count($consultation)>0) {	
					$this->openConsultationId=$consultation[0]->consultation_id;
					Log::info($consultation[0]->consultation_id);
					return $consultation[0]->consultation_id;
			} else {
					return 0;
			}
	}
}