<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ConsultationDiagnosis extends Model
{
	protected $table = 'consultation_diagnoses';
	protected $fillable = [
				'consultation_id',
				'diagnosis_type',
				'diagnosis_is_principal',
				'diagnosis_clinical'];

	public function validate($input, $method) {
			$rules = [
				'consultation_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function diagnosisType()
	{
			return $this->belongsTo('App\DiagnosisType', 'diagnosis_type','type_code');
	}


}
