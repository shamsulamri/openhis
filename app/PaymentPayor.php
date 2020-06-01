<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PaymentPayor extends Model
{
	protected $table = 'payment_payors';
	protected $fillable = [
				'encounter_id',
				'payor_first_name',
				'payor_last_name',
				'payor_address_1',
				'payor_address_2',
				'payor_city',
				'payor_postcode',
				'nation_code',
				'state_code',
				'payor_phone_number'];
	
    protected $guarded = ['payor_id'];
    protected $primaryKey = 'payor_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
			];

			/*
				'payor_first_name'=>'required',
				'payor_last_name'=>'required',
				'payor_address_1'=>'required',
				'payor_city'=>'required',
				'payor_postcode'=>'required',
				'nation_code'=>'required',
				'payor_phone_number'=>'required',
			*/
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
