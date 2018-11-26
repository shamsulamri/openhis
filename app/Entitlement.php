<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Entitlement extends Model
{
	protected $table = 'ref_entitlements';
	protected $fillable = [
				'entitlement_code',
				'entitlement_name'];
	
    protected $guarded = ['entitlement_code'];
    protected $primaryKey = 'entitlement_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'entitlement_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['entitlement_code'] = 'required|max:20|unique:ref_entitlements';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}