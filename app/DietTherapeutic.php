<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietTherapeutic extends Model
{
	protected $table = 'diet_therapeutics';
	protected $fillable = [
				'therapeutic_code',
				'therapeutic_name'];
	
    protected $guarded = ['therapeutic_code'];
    protected $primaryKey = 'therapeutic_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'therapeutic_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['therapeutic_code'] = 'required|max:20|unique:diet_therapeutics';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}