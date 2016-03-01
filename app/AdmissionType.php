<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class AdmissionType extends Model
{
	protected $table = 'ref_admissions';
	protected $fillable = [
				'admission_code',
				'admission_name'];
	
    protected $guarded = ['admission_code'];
    protected $primaryKey = 'admission_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'admission_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['admission_code'] = 'required|max:10|unique:ref_admissions';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}