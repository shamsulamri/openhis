<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Employer extends Model
{
	protected $table = 'employers';
	protected $fillable = [
				'employer_code',
				'employer_name',
				'employer_street_1',
				'employer_street_2',
				'employer_city',
				'employer_postcode',
				'employer_state',
				'employer_country',
				'employer_phone'];
	
    protected $guarded = ['employer_code'];
    protected $primaryKey = 'employer_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'employer_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['employer_code'] = 'required|max:20|unique:employers';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}