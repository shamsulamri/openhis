<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Sponsor extends Model
{
	protected $table = 'sponsors';
	protected $fillable = [
				'sponsor_name',
				'sponsor_street_1',
				'sponsor_street_2',
				'sponsor_city',
				'sponsor_postcode',
				'sponsor_state',
				'sponsor_country',
				'sponsor_phone'];
	
    protected $guarded = ['sponsor_code'];
    protected $primaryKey = 'sponsor_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'sponsor_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['sponsor_code'] = 'required|max:20|unique:sponsors';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}