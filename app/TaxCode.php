<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class TaxCode extends Model
{
	protected $table = 'tax_codes';
	protected $fillable = [
				'tax_code',
				'tax_name',
				'tax_rate'];
	
    protected $guarded = ['tax_code'];
    protected $primaryKey = 'tax_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'tax_name'=>'required',
				'tax_rate'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['tax_code'] = 'required|max:20.0|unique:tax_codes';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}
	
}
