<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class Order extends Model
{
	protected $table = 'orders';
	protected $fillable = [
				'consultation_id',
				'user_id',
				'product_code',
				'order_quantity_request',
				'order_description',
				'order_report',
				'order_completed',
				'order_completed_by',
				'order_quantity_supply',
				'store_code',
				'location_code',
				'order_sale_price',
				'order_unit_price',
				'order_total',
				'order_discount',
				'order_gst_unit',
				'order_discharge',
				'update_by',
		];
	
    protected $guarded = ['order_id'];
    protected $primaryKey = 'order_id';
    public $incrementing = true;

	protected $defaults = [
			'order_quantity_request'=>'1',
			'order_quantity_supply'=>'1',
	];

	public function __construct(array $attributes = array())
	{
			    $this->setRawAttributes($this->defaults, true);
				    parent::__construct($attributes);
	}

	public function validate($input, $method) {
			$rules = [];
			switch ($method) {
					case "PUT":
							
							break;
					default:
							$rules = [
								'consultation_id'=>'required',
								'user_id'=>'required',
								'product_code'=>'required',
								'order_quantity_request'=>'required|min:1',
							];
			}

			
			
			$messages = [
				'required' => 'This field is required'
			];

			$validator =  validator::make($input, $rules ,$messages);

			return $validator;
	}

	/*
	public function setOrderQuantityRequestAttribute()
	{
			if ($this->attributes['order_quantity_request']==0) {
					$this->attributes['order_quantity_request']=1;
			}
	}
	 */

	public function orderDrug() {
			return $this->hasOne('App\OrderDrug','order_id');
	}

	public function consultation()
	{
			return $this->belongsTo('App\Consultation','consultation_id');
	}

	public function product()
	{
			return $this->belongsTo('App\Product', 'product_code');
	}

	public function orderCancel()
	{
			return $this->hasOne('App\OrderCancellation','order_id');
	}

	public function orderInvestigation() 
	{
			return $this->hasOne('App\OrderInvestigation','order_id');
	}

	public function user()
	{
			return $this->belongsTo('App\User', 'user_id','id');
	}

	public static function boot()
	{
			parent::boot();

			static::deleted(function($order)
			{
				$order->orderInvestigation()->delete();
				$order->orderDrug()->delete();
			});
	}

	public function save(array $options = array())
	{
			$changed = $this->isDirty() ? $this->getDirty() : false;

			parent::save();

			if ($changed) 
			{
				Log::info($this->product_code);
			}	
	}
}
