<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class OrderMaintenance extends Model
{
	protected $table = 'orders';
	protected $fillable = [
				'order_custom_id',
				'encounter_id',
				'consultation_id',
				'user_id',
				'admission_id',
				'ward_code',
				'location_code',
				'store_code',
				'product_code',
				'bom_code',
				'order_description',
				'order_completed',
				'order_multiple',
				'order_quantity_request',
				'order_quantity_supply',
				'order_unit_price',
				'unit_code',
				'order_discount',
				'order_markup',
				'order_is_discharge',
				'order_is_future',
				'order_include_stat',
				'order_report',
				'reported_by',
				'order_diagnostic_report',
				'updated_by',
				'completed_by',
				'completed_at',
				'dispensed_at',
				'dispensed_by',
				'post_id',
				'origin_id'];
	
    protected $guarded = ['order_id'];
    protected $primaryKey = 'order_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'consultation_id'=>'required',
				'user_id'=>'required',
				'product_code'=>'required',
				'order_quantity_request'=>'required',
				'unit_code'=>'required',
				'post_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function product()
	{
			return $this->belongsTo('App\Product', 'product_code');
	}
	
}
