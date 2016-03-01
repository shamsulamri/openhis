<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Urgency extends Model
{
	protected $table = 'ref_urgencies';
	protected $fillable = [
				'urgency_code',
				'urgency_name'];
	
    protected $guarded = ['urgency_code'];
    protected $primaryKey = 'urgency_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'urgency_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['urgency_code'] = 'required|max:20|unique:ref_urgencies';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}