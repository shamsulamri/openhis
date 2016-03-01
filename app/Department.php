<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Department extends Model
{
	protected $table = 'departments';
	protected $fillable = [
				'department_name'];
	
    protected $guarded = ['department_code'];
    protected $primaryKey = 'department_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'department_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['department_code'] = 'required|max:20|unique:departments';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}