<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Ward extends Model
{
	protected $table = 'wards';
	protected $fillable = [
				'ward_code',
				'encounter_code',
				'gender_code',
				'department_code',
				'ward_name'];
	
    protected $guarded = ['ward_code'];
    protected $primaryKey = 'ward_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'ward_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['ward_code'] = 'required|max:20|unique:wards';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
