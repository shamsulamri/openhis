<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Gender extends Model
{
	protected $table = 'ref_genders';
	protected $fillable = [
				'gender_code',
				'gender_name'];
	
    protected $guarded = ['gender_code'];
    protected $primaryKey = 'gender_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'gender_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['gender_code'] = 'required|max:1|unique:ref_genders';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}