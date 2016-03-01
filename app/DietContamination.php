<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietContamination extends Model
{
	protected $table = 'diet_contaminations';
	protected $fillable = [
				'contamination_code',
				'contamination_name'];
	
    protected $guarded = ['contamination_code'];
    protected $primaryKey = 'contamination_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'contamination_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['contamination_code'] = 'required|max:20|unique:diet_contaminations';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}