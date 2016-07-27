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
				'author_name',
				'module_patient',
				'module_consultation',
				'module_inventory',
				'module_ward',
				'module_support',
				'module_discharge',
				'module_diet',
				'module_medical_record',
				'patient_list',
				'product_list',
				'loan_function',
				'appointment_function',
				'system_administrator'];
	
    protected $guarded = ['author_id'];
    protected $primaryKey = 'author_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'author_name'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
