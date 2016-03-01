<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Drug extends Model
{
	protected $table = 'drugs';
	protected $fillable = [
				'category_code',
				'drug_trade_name',
				'drug_generic_name',
				'drug_registration_number',
				'drug_unit_charge'];
	
    protected $guarded = ['drug_code'];
    protected $primaryKey = 'drug_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [

			];

			
        	if ($method=='') {
        	    $rules['drug_code'] = 'required|max:20.0|unique:drugs';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}