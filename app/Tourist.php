<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Tourist extends Model
{
	protected $table = 'ref_tourists';
	protected $fillable = [
				'tourist_code',
				'tourist_name'];
	
    protected $guarded = ['tourist_code'];
    protected $primaryKey = 'tourist_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [

			];

			
        	if ($method=='') {
        	    $rules['tourist_code'] = 'required|max:20|unique:ref_tourists';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}