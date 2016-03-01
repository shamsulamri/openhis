<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietEnteral extends Model
{
	protected $table = 'diet_enterals';
	protected $fillable = [
				'enteral_code',
				'enteral_name'];
	
    protected $guarded = ['enteral_code'];
    protected $primaryKey = 'enteral_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'enteral_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['enteral_code'] = 'required|max:10|unique:diet_enterals';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}