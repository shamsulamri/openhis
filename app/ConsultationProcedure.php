<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ConsultationProcedure extends Model
{
	protected $table = 'consultation_procedures';
	protected $fillable = [
				'procedure_description',
				'procedure_is_principal',
				'consultation_id'];
	

	public function validate($input, $method) {
			$rules = [
				'consultation_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}