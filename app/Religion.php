<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Religion extends Model
{
	protected $table = 'ref_religions';
	protected $fillable = [
				'religion_code',
				'religion_name'];
	
    protected $guarded = ['religion_code'];
    protected $primaryKey = 'religion_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'religion_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['religion_code'] = 'required|max:20|unique:ref_religions';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}