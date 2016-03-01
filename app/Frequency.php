<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Frequency extends Model
{
	protected $table = 'ref_frequencies';
	protected $fillable = [
				'frequency_code',
				'frequency_name'];
	
    protected $guarded = ['frequency_code'];
    protected $primaryKey = 'frequency_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'frequency_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['frequency_code'] = 'required|max:20|unique:ref_frequencies';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}