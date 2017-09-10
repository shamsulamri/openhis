<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Priority extends Model
{
	protected $table = 'ref_priorities';
	protected $fillable = [
				'priority_name'];
	
    protected $guarded = ['priority_code'];
    protected $primaryKey = 'priority_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'priority_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['priority_code'] = 'required|max:20|unique:ref_priorities';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}