<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use App\StockHelper;
use App\Document;
use Log;

class Order extends Model
{
	protected $table = 'orders';
	protected $fillable = [
				'order_custom_id',
				'consultation_id',
				'user_id',
				'admission_id',
				'product_code',
				'bom_code',
				'ward_code',
				'location_code',
				'store_code',
				'order_quantity_request',
				'order_description',
				'order_report',
				'reported_by',
				'order_diagnostic_report',
				'order_completed',
				'order_completed_by',
				'order_quantity_supply',
				'unit_code',
				'order_unit_price',
				'order_discount',
				'order_markup',
				'order_is_discharge',
				'order_include_stat',
				'order_is_future',
				'updated_by',
				'completed_at',
				'completed_by',
				'dispensed_at',
				'dispensed_by',
				'created_at',
				'post_id',
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

	public function orderPost() {
			return $this->hasOne('App\OrderPost', 'post_id');
	}

	public function orderDate() {
			return $this->attributes['created_at'];
	}

	public function orderDrug() {
			return $this->hasOne('App\OrderDrug','order_id');
	}

	public function drugLabel() {
			return $this->hasOne('App\OrderDrugLabel','order_id');
	}


	public function consultation()
	{
			return $this->belongsTo('App\Consultation','consultation_id');
	}

	public function location()
	{
			return $this->belongsTo('App\QueueLocation','location_code');
	}

	public function product()
	{
			return $this->belongsTo('App\Product', 'product_code');
	}

	public function bom()
	{
			return $this->belongsTo('App\Product', 'bom_code', 'product_code');
	}

	public function drug()
	{
			return $this->belongsTo('App\Drug', 'drug_code', 'product_code');
	}

	public function store()
	{
			return $this->belongsTo('App\Store', 'store_code');
	}

	public function orderCancel()
	{
			return $this->hasOne('App\OrderCancellation','order_id');
	}
	
	public function document()
	{
			return $this->hasOne('App\Document', 'order_id');
	}

	public function orderInvestigation() 
	{
			return $this->hasOne('App\OrderInvestigation','order_id');
	}

	public function user()
	{
			return $this->belongsTo('App\User', 'user_id','id');
	}

	public function updateBy()
	{
			return $this->belongsTo('App\User', 'updated_by','id');
	}

	public function admission()
	{
			return $this->belongsTo('App\Admission', 'admission_id');
	}

	public static function boot()
	{
			parent::boot();

			static::deleted(function($order)
			{
				Log::info("Deleted !!!!!");
				$order->orderInvestigation()->delete();
				$order->orderDrug()->delete();
				$order->drugLabel()->delete();
			});

			static::created(function($order)
			{

			});


	}

	public function save(array $options = array())
	{
			$changed = $this->isDirty() ? $this->getDirty() : false;

			parent::save();

			if ($changed) 
			{
				//Log::info("Changed!!!!");
			}	

	}


	public function setCompletedAtAtrribute($value)
	{
		$this->attributes['completed_at'] = DojoUtility::dateTimeWriteFormat($value);
	}
}
