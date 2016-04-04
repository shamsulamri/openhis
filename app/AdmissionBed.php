<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class AdmissionBed extends Model
{
	protected $table = 'beds';
	protected $fillable = [
				'bed_code',
				'encounter_code',
				'class_code',
				'ward_code',
				'room_code',
				'status_code',
				'bed_name',
				'bed_virtual',
				'gender_code',
				'department_code'];
	
    protected $guarded = ['bed_code'];
    protected $primaryKey = 'bed_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_code'=>'required',
				'ward_code'=>'required',
				'bed_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['bed_code'] = 'required|max:20.0|unique:beds';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}