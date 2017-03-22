<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class TaxType extends Model
{
	protected $table = 'tax_types';
	protected $fillable = [
				'type_name'];
	
    protected $guarded = ['type_code'];
    protected $primaryKey = 'type_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'type_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['type_code'] = 'required|max:20|unique:tax_types';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}