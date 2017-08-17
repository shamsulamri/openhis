<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DrugIndication extends Model
{
	protected $table = 'drug_indications';
	protected $fillable = [
				'indication_description'];
	
    protected $guarded = ['indication_code'];
    protected $primaryKey = 'indication_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [

			];

			
        	if ($method=='') {
        	    $rules['indication_code'] = 'required|max:20|unique:drug_indications';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}