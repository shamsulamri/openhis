<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ProductCharge extends Model
{
	protected $table = 'product_charges';
	protected $fillable = [
				'charge_code',
				'charge_name'];
	
    protected $guarded = ['charge_code'];
    protected $primaryKey = 'charge_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'charge_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['charge_code'] = 'required|max:20|unique:product_charges';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}