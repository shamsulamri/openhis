<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Bill extends Model
{
	protected $table = 'orders';
	protected $fillable = [
				'order_quantity_supply',
				'order_sale_price',
				'order_discount',
				'order_exempted',
				'order_total'];
	
    protected $guarded = ['order_id'];
    protected $primaryKey = 'order_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'order_quantity_supply'=>'required',
				'order_sale_price'=>'required',
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
