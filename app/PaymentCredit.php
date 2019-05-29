<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PaymentCredit extends Model
{
	protected $table = 'payment_credits';
	protected $fillable = [
				'card_code',
				'credit_number',
				'credit_expiry_month',
				'credit_expiry_year',
				'credit_first_name',
				'credit_last_name',
				'credit_address_1',
				'credit_address_2',
				'credit_city',
				'credit_postcode',
				'nation_code',
				'credit_phone_number'];
	
    protected $guarded = ['credit_id'];
    protected $primaryKey = 'credit_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'card_code'=>'required',
				'credit_number'=>'required',
				'credit_expiry_month'=>'required',
				'credit_expiry_year'=>'required',
				'credit_first_name'=>'required',
				'credit_last_name'=>'required',
			];

			/*
				'credit_address_1'=>'required',
				'credit_address_2'=>'required',
				'credit_city'=>'required',
				'credit_postcode'=>'required',
				'nation_code'=>'required',
				'credit_phone_number'=>'required',
			*/
			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
