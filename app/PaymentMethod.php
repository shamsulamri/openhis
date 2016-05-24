<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PaymentMethod extends Model
{
	protected $table = 'payment_methods';
	protected $fillable = [
				'payment_code',
				'payment_name'];
	
    protected $guarded = ['payment_code'];
    protected $primaryKey = 'payment_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'payment_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['payment_code'] = 'required|max:20|unique:payment_methods';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}