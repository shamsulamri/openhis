<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class CreditCard extends Model
{
	protected $table = 'credit_cards';
	protected $fillable = [
				'card_code',
				'card_name'];
	
    protected $guarded = ['card_code'];
    protected $primaryKey = 'card_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'card_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['card_code'] = 'required|max:20|unique:credit_cards';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}