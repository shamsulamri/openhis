<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class UserAuthorization extends Model
{
	protected $table = 'user_authorizations';
	protected $fillable = [
				'id',
				'module_patient',
				'module_consultation',
				'module_inventory',
				'module_ward',
				'module_support',
				'module_discharge',
				'module_diet',
				'module_medical_record',
				'system_administrator',
		];
	

	public function validate($input, $method) {
			$rules = [
				'module_consultation'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
