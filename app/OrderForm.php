<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class OrderForm extends Model
{
	protected $table = 'order_forms';
	protected $fillable = [
				'form_code',
				'form_name'];
	
    	protected $guarded = ['form_code'];
    	protected $primaryKey = 'form_code';
    	public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'form_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['form_code'] = 'required|max:20|unique:order_forms';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}