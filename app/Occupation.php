<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Occupation extends Model
{
	protected $table = 'ref_occupations';
	protected $fillable = [
				'occupation_code',
				'occupation_name'];
	
    protected $guarded = ['occupation_code'];
    protected $primaryKey = 'occupation_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'occupation_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['occupation_code'] = 'required|max:20|unique:ref_occupations';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}