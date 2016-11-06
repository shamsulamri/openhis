<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Bill extends Model
{
	protected $table = 'bills';
	protected $fillable = [
				'encounter_id',
				'bill_grand_total',
				'bill_payment_total',
				'bill_deposit_total',
				'bill_outstanding',
				'user_id',
				'bill_change'];
	

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'bill_grand_total'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
