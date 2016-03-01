<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class City extends Model
{
	protected $table = 'ref_cities';
	protected $fillable = [
				'city_name',
				'state_code'];
	
    protected $guarded = ['city_code'];
    protected $primaryKey = 'city_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'city_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['city_code'] = 'required|max:20|unique:ref_cities';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}