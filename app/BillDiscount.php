<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BillDiscount extends Model
{
	protected $table = 'bill_discounts';
	protected $fillable = [
				'encounter_id',
				'discount_amount'];
	
    protected $guarded = ['discount_id'];
    protected $primaryKey = 'discount_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'discount_amount'=>'required|numeric|min:1',
			];

			
			
			$messages = [
				'required' => 'This field is required',
				'min' => 'Must be greater than 0',
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
