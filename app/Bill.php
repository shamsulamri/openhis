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
				'bill_name',
				'bill_non_claimable',
				'bill_total',
				'bill_total_after_discount',
				'bill_discount',
				'bill_markup',
				'bill_grand_total',
				'bill_payment_total',
				'bill_deposit_total',
				'bill_outstanding',
				'user_id',
				'bill_change'];
	
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    public $incrementing = true;

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function encounter()
	{
			return $this->hasOne('App\Encounter', 'encounter_id', 'encounter_id');
	}
}
