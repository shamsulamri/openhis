<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Order extends Model
{
	protected $table = 'orders';
	protected $fillable = [
				'consultation_id',
				'product_code',
				'order_quantity_request',
				'order_description',
				'order_completed',
				'order_quantity_supply',
				'location_code',
				'order_sale_price',
				'order_total',
				'order_discount',
				'order_discharge'];
	
    protected $guarded = ['order_id'];
    protected $primaryKey = 'order_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'consultation_id'=>'required',
				'product_code'=>'required',
				'order_quantity_request'=>'required|min:1',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function orderDrug() {
			return $this->hasOne('App\OrderDrug','order_id');
	}
}
