<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Diet extends Model
{
	protected $table = 'diets';
	protected $fillable = [
				'diet_code',
				'diet_name'];
	
    protected $guarded = ['diet_code'];
    protected $primaryKey = 'diet_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'diet_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['diet_code'] = 'required|max:20|unique:diets';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}