<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DrugFrequency extends Model
{
	protected $table = 'drug_frequencies';
	protected $fillable = [
				'frequency_name',
				'frequency_label',
				'frequency_value'];
	
    protected $guarded = ['frequency_code'];
    protected $primaryKey = 'frequency_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'frequency_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['frequency_code'] = 'required|max:20.0|unique:drug_frequencies';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}