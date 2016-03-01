<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class CareLevel extends Model
{
	protected $table = 'ref_care_levels';
	protected $fillable = [
				'care_code',
				'care_name'];
	
    protected $guarded = ['care_code'];
    protected $primaryKey = 'care_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'care_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['care_code'] = 'required|max:20|unique:ref_care_levels';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}