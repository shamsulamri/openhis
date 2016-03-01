<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietRating extends Model
{
	protected $table = 'diet_ratings';
	protected $fillable = [
				'rate_code',
				'rate_name',
				'rate_position'];
	
    protected $guarded = ['rate_code'];
    protected $primaryKey = 'rate_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'rate_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['rate_code'] = 'required|max:20.0|unique:diet_ratings';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}