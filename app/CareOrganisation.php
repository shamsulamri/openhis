<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class CareOrganisation extends Model
{
	protected $table = 'care_organisations';
	protected $fillable = [
				'organisation_code',
				'organisation_name'];
	
    protected $guarded = ['organisation_code'];
    protected $primaryKey = 'organisation_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'organisation_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['organisation_code'] = 'required|max:20|unique:care_organisations';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}