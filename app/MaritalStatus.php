<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class MaritalStatus extends Model
{
	protected $table = 'ref_marital_statuses';
	protected $fillable = [
				'marital_code',
				'marital_name'];
	
    protected $guarded = ['marital_code'];
    protected $primaryKey = 'marital_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'marital_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['marital_code'] = 'required|max:20|unique:ref_marital_statuses';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}