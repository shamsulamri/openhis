<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class AdmissionTask extends Model
{
	protected $table = 'orders';
	protected $fillable = [
				'encounter_id',
				'consultation_id',
				'user_id',
				'product_code',
				'order_quantity_request',
				'order_description',
				'order_completed',
				'order_quantity_supply',
				'location_code',
				'order_sale_price',
				'order_discount',
				'order_is_discharge',
				'order_report',
				'post_id'];
	
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

	public function user()
	{
			return $this->belongsTo('App\User', 'user_id','id');
	}

	public function save(array $options = array())
	{
			$changed = $this->isDirty() ? $this->getDirty() : false;

			parent::save();

			if ($changed) 
			{
				Log::info("Changed:".$changed);
			}	
	}
	
}
