<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PatientFlag extends Model
{
	protected $table = 'ref_patient_flags';
	protected $fillable = [
				'flag_code',
				'flag_name'];
	
    protected $guarded = ['flag_code'];
    protected $primaryKey = 'flag_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'flag_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['flag_code'] = 'required|max:20|unique:ref_patient_flags';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}