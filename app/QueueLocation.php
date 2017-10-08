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
				'location_is_pool',
				'department_code',
				'encounter_code',
				'store_code',
				'location_level',
				'user_id'];
	
    protected $guarded = ['location_code'];
    protected $primaryKey = 'location_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'location_name'=>'required',
				'store_code'=>'required',
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

	public function department()
	{
			return $this->belongsTo('App\Department', 'department_code');
	}

	public function store()
	{
			return $this->belongsTo('App\Store', 'store_code');
	}

	public function encounter()
	{
			return $this->belongsTo('App\EncounterType', 'encounter_code');
	}
	
}
