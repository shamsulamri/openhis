<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Supplier extends Model
{
	protected $table = 'suppliers';
	protected $fillable = [
				'supplier_code',
				'supplier_name',
				'supplier_company_number',
				'supplier_street_1',
				'supplier_street_2',
				'supplier_city',
				'supplier_postcode',
				'supplier_state',
				'supplier_country',
				'supplier_phone',
				'supplier_person',
				'supplier_account'];
	
    	protected $guarded = ['supplier_code'];
    	protected $primaryKey = 'supplier_code';
    	public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'supplier_name'=>'required',
				'supplier_company_number'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['supplier_code'] = 'required|max:20|unique:suppliers';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function state()
	{
			return $this->belongsTo('App\State', 'supplier_state', 'state_code');
	}
	
}
