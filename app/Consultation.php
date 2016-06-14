<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use App\Patient;

class Consultation extends Model
{
	protected $table = 'consultations';
	protected $fillable = [
				'encounter_id',
				'patient_id',
				'user_id',
				'consultation_status',
				'consultation_notes',
				];
	
    protected $guarded = ['consultation_id'];
    protected $primaryKey = 'consultation_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'patient_id'=>'required',
				'user_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function getConsultationNote()
	{
			$note = $this->consultation_note;
			$note = str_replace(chr(13), "<br>", $note);
			return $note;
	}

	public function encounter()
	{
			return $this->belongsTo('App\Encounter');
	}

 	public function user()
	{
			return $this->hasOne('App\User','id','user_id');
	}

	public function orders() 
	{
			return $this->hasMany('App\Order', 'consultation_id');
	}

	public function diagnoses()
	{
			return $this->hasMany('App\ConsultationDiagnosis', 'consultation_id');
	}

	public function procedures()
	{
			return $this->hasMany('App\ConsultationProcedure', 'consultation_id');
	}

	public function queue()
	{
			return $this->hasOne('App\Queue', 'encounter_id','encounter_id');
	}
	
	public function medical_certificate()
	{
			return $this->hasOne('App\MedicalCertificate', 'consultation_id');
	}
	
	public static function boot()
	{
			parent::boot();

			static::deleted(function($consultation)
			{
				$consultation->orders()->delete();
				$consultation->diagnoses()->delete();
				$consultation->procedures()->delete();
			});
	}

}
