<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class EncounterType extends Model
{
	protected $table = 'ref_encounter_types';
	protected $fillable = [
				'encounter_code',
				'encounter_name'];
	
    protected $guarded = ['encounter_code'];
    protected $primaryKey = 'encounter_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['encounter_code'] = 'required|max:20|unique:ref_encounter_types';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}