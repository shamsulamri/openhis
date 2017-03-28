<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class FormSystem extends Model
{
	protected $table = 'form_systems';
	protected $fillable = [
				'system_code',
				'system_name'];
	
    protected $guarded = ['system_code'];
    protected $primaryKey = 'system_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'system_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['system_code'] = 'required|max:20|unique:form_systems';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}