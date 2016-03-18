<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

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
				'diet_code',
				'texture_code',
				'class_code',
				'admission_nbm'];
	
    protected $guarded = ['admission_id'];
    protected $primaryKey = 'admission_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'user_id'=>'required',
				'bed_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
