<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Referral extends Model
{
	protected $table = 'ref_referrals';
	protected $fillable = [
				'referral_code',
				'referral_name'];
	
    protected $guarded = ['referral_code'];
    protected $primaryKey = 'referral_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'referral_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['referral_code'] = 'required|max:20|unique:ref_referrals';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}