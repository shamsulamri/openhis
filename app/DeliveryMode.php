<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DeliveryMode extends Model
{
	protected $table = 'ref_delivery_modes';
	protected $fillable = [
				'delivery_name'];
	
    protected $guarded = ['delivery_code'];
    protected $primaryKey = 'delivery_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'delivery_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['delivery_code'] = 'required|max:20|unique:ref_delivery_modes';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}