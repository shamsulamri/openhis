<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BillItem extends Model
{
	protected $table = 'bill_items';
	protected $fillable = [
				'encounter_id',
				'order_id',
				'product_code',
				'tax_code',
				'tax_rate',
				'bill_name',
				'bill_discount',
				'bill_markup',
				'bill_quantity',
				'bill_unit_code',
				'bill_unit_price',
				'bill_unit_multiplier',
				'bill_amount',
				'bill_amount_exclude_tax',
				'bill_non_claimable',
				'bill_description',
				'bill_exempted'];
	
    protected $guarded = ['bill_id'];
    protected $primaryKey = 'bill_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'bill_quantity'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function encounter()
	{
			return $this->belongsTo('App\Encounter','encounter_id');
	}

	public function product()
	{
			return $this->belongsTo('App\Product', 'product_code');
	}
	
}
