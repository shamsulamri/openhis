<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use App\Consultation;
use App\Encounter;
use Log;
use DB;

class Admission extends Model
{
	protected $table = 'admissions';
	protected $fillable = [
				'encounter_id',
				'user_id',
				'team_code',
				'bed_code',
				'admission_code',
				'referral_code',
				'employer_code',
				'employee_id',
				'organisation_code',
				'organisation_id',
				'diet_code',
				'therapeutic_values',
				'texture_code',
				'class_code',
				'diet_description',
				'nbm_duration',
				'nbm_datetime',
				'period_code',
				'diet_textures',
				'block_room',
				'anchor_bed',
				'nbm_status'];
	
    protected $guarded = ['admission_id'];
    protected $primaryKey = 'admission_id';
    public $incrementing = true;
	//public $openConsultationId=0;    

	public function validate($input, $method) {
			$rules = [];

			switch ($method) {
			case "POST":
					$rules = [
						'encounter_id'=>'required',
						'user_id'=>'required',
					];
					break;
			default:
					
					$encounter = Encounter::find($input['encounter_id']);
					Log::info($encounter->encounter_code);
					if ($encounter->encounter_code=='mortuary') {
							$rules = [
								'encounter_id'=>'required',
							];
					} else {
							$rules = [
								'encounter_id'=>'required',
								'user_id'=>'required',
							];
					}
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

	public function team() 
	{
			return $this->belongsTo('App\Team', 'team_code');
	}

	public function consultant() 
	{
			return $this->belongsTo('App\User', 'user_id');
	}

	public function bed()
	{
			return $this->hasOne('App\Bed', 'bed_code', 'bed_code');
	}

	public function anchorBed()
	{
			return $this->hasOne('App\Bed', 'bed_code', 'anchor_bed');
	}

	public function arrival()
	{
			return $this->hasOne('App\WardArrival', 'encounter_id', 'encounter_id');
	}

	public function period()
	{
			return $this->belongsTo('App\Period', 'period_code', 'period_code');
	}

	public function bedMovement()
	{
			return $this->hasMany('App\BedMovement', 'admission_id'); 
	}

	/**
	public function hasOpenConsultation($patientId, $encounter_id)
	{
			$consultation = Consultation::where('patient_id','=',$patientId)
					->where('encounter_id',$encounter_id)
					->where('consultation_status',1)
					->get();
			
		    if (count($consultation)>0) {	
					$this->openConsultationId=$consultation[0]->consultation_id;
					Log::info($consultation[0]->consultation_id);
					return $consultation[0]->consultation_id;
			} else {
					return 0;
			}
	}
	**/

}
