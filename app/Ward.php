<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class Ward extends Model
{
	protected $table = 'wards';
	protected $fillable = [
				'ward_code',
				'encounter_code',
				'gender_code',
				'department_code',
				'store_code',
				'ward_level',
				'ward_omission',
				'ward_name'];
	
    protected $guarded = ['ward_code'];
    protected $primaryKey = 'ward_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'ward_name'=>'required',
				'department_code'=>'required',
				'encounter_code'=>'required',
				'ward_level'=>'numeric',
			];

			
        	if ($method=='') {
        	    $rules['ward_code'] = 'required|max:20|unique:wards';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function gender()
	{
			return $this->belongsTo('App\Gender', 'gender_code');
	}

	public function department()
	{
			return $this->belongsTo('App\Department', 'department_code');
	}
}
