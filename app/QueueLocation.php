<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class QueueLocation extends Model
{
	protected $table = 'queue_locations';
	protected $fillable = [
				'location_name',
				'location_code',
				'encounter_code',
				'user_id'];
	
    protected $guarded = ['location_code'];
    protected $primaryKey = 'location_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'location_name'=>'required',
				'encounter_code'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['location_code'] = 'required|max:20.0|unique:queue_locations';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
