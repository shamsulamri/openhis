<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Registration extends Model
{
	protected $table = 'ref_registrations';
	protected $fillable = [
				'registration_code',
				'registration_name',
				'billing_group'];
	protected $guarded = ['registration_code'];
	protected $primaryKey = 'registration_code';
	public $incrementing = false;

	public function validate($input, $method) {
			$rules = [
                
				'registration_name'=>'required|max:50',
			];

			if ($method=='') {
				$rules['registration_code'] = 'required|max:10|unique:ref_registrations';
			}
			
			$messages = [
				'required' => 'The :attribute field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}