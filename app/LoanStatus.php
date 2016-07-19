<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class LoanStatus extends Model
{
	protected $table = 'ref_loan_statuses';
	protected $fillable = [
				'loan_code',
				'loan_name'];
	
    protected $guarded = ['loan_code'];
    protected $primaryKey = 'loan_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'loan_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['loan_code'] = 'required|max:20|unique:ref_loan_statuses';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}