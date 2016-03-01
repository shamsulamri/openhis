<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class FormProperty extends Model
{
	protected $table = 'form_properties';
	protected $fillable = [
				'property_code',
				'property_name',
				'property_type',
				'property_unit',
				'property_limit_1',
				'property_limit_2',
				'property_limit_type',
				'property_list',
				'property_shortname',
				'property_system',
				'property_multiline'];
	
    	protected $guarded = ['property_code'];
    	protected $primaryKey = 'property_code';
    	public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'property_name'=>'required',
				'property_type'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['property_code'] = 'required|max:20.0|unique:form_properties';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}