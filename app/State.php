<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class State extends Model
{
	protected $table = 'ref_states';
	protected $fillable = [
				'state_code',
				'state_name'];
	
    protected $guarded = ['state_code'];
    protected $primaryKey = 'state_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'state_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['state_code'] = 'required|max:20|unique:ref_states';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}