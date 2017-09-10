<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BedTransaction extends Model
{
	protected $table = 'bed_transactions';
	protected $fillable = [
				'transaction_name'];
	
    protected $guarded = ['transaction_code'];
    protected $primaryKey = 'transaction_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'transaction_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['transaction_code'] = 'required|max:20|unique:bed_transactions';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}