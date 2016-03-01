<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BirthComplication extends Model
{
	protected $table = 'ref_birth_complications';
	protected $fillable = [
				'complication_name'];
	
    protected $guarded = ['complication_code'];
    protected $primaryKey = 'complication_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'complication_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['complication_code'] = 'required|max:20|unique:ref_birth_complications';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}