<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PatientBilling extends Model
{
	protected $table = 'patient_billings';
	protected $fillable = [
				'encounter_id',
				'order_id',
				'product_code',
				'tax_code',
				'tax_rate',
				'bill_discount',
				'bill_quantity',
				'bill_unit_price',
				'bill_total',
				'bill_exempted'];
	
    protected $guarded = ['bill_id'];
    protected $primaryKey = 'bill_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'order_id'=>'required',
				'product_code'=>'required',
				'tax_code'=>'required',
				'tax_rate'=>'required',
				'bill_discount'=>'required',
				'bill_quantity'=>'required',
				'bill_unit_price'=>'required',
				'bill_total'=>'required',
				'bill_exempted'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}