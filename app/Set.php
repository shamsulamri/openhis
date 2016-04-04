<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Set extends Model
{
	protected $table = 'ref_sets';
	protected $fillable = [
				'set_code',
				'set_name'];
	
    protected $guarded = ['set_code'];
    protected $primaryKey = 'set_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'set_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['set_code'] = 'required|max:20|unique:ref_sets';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}