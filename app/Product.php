<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;
use App\GeneralLedger;

class Product extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'products';
	protected $fillable = [
				'product_name',
				'product_name_other',
				'category_code',
				'unit_code',
				'order_form',
				'product_upc',
				'product_sku',
				'product_unit_charge',
				'product_purchased',
				'product_purchase_unit',
				'product_stocked',
				'product_sold',
				'product_drop_charge',
				'product_dismantle_material',
				'product_sale_price',
				'product_bom',
				'product_reorder',
				'product_purchase_price',
				'purchase_tax_code',
				'location_code',
				'form_code',
				'charge_code',
				'product_track_batch',
				'status_code',
				'product_average_cost',
				'product_cost',
				'product_conversion_unit',
				'product_conversion_code',
				'product_sale_margin',
				'product_on_hand',
				'product_drop_charge',
				'product_local_store',
				'tax_code'];
	
    	protected $guarded = ['product_code'];
    	protected $primaryKey = 'product_code';
    	public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'product_name'=>'required',
				'category_code'=>'required',
				'order_form'=>'required',
				'product_sale_price'=>'required_if:product_sold,==,"1"'
			];

			
        	if ($method=='') {
        	    $rules['product_code'] = 'required|max:20.0|unique:products|alpha_dash';
        	}
        
			
			$messages = [
					'required' => 'This field is required',
					'product_sale_price.required_if' => 'Field required when product is sold to patient',
			];

        	if ($method=='PUT') {
				$product = Product::find($this->attributes['product_code']);
        	   	$rules['product_purchase_price'] = 'greater_than_or_equal:'.$this->attributes['product_purchase_price'].','.$product->product_purchase_price;
				$messages['greater_than_or_equal']="Purchase prices cannot be lower than last recorded price.";
        	}



			Log::info($rules);
			Log::info($messages);
			
			return validator::make($input, $rules ,$messages);
	}

	public function setProductNameAttribute($value)
	{
			$this->attributes['product_name'] = strtoupper($value);
	}

	public function getProductNameAttribute($value)
	{
			return strtoupper($value);
	}

	public function category()
	{
			return $this->belongsTo('App\ProductCategory','category_code');
	}

	public function unitMeasure()
	{
			return $this->belongsTo('App\UnitMeasure', 'unit_code');
	}

	public function purchaseUnitMeasure()
	{
			return $this->belongsTo('App\UnitMeasure', 'product_purchase_unit');
	}

	public function orderForm()
	{
			return $this->belongsTo('App\OrderForm', 'order_form');
	}

	public function resultForm()
	{
			return $this->belongsTo('App\Form', 'form_code');
	}

	public function tax()
	{
			return $this->belongsTo('App\TaxCode', 'tax_code');
	}

	public function purchase_tax()
	{
			return $this->belongsTo('App\TaxCode', 'purchase_tax_code');
	}

	public function location()
	{
			return $this->belongsTo('App\QueueLocation', 'location_code');
	}

	public function status()
	{
			return $this->belongsTo('App\ProductStatus', 'status_code');
	}

	public function drug()
	{
			return $this->hasOne('App\Drug', 'drug_code', 'product_code');
	}

	public function getOrderFormName()
	{
			if ($this->attributes['order_form']) {
				return OrderForm::find($this->attributes['order_form'])->form_name;
			} else {
				return "";
			}

	}

	public function getFormName()
	{
			if ($this->attributes['form_code']) {
				return Form::find($this->attributes['form_code'])->form_name;
			} else {
				return "-";
			}
	}

	public function getLocationName()
	{
			if ($this->attributes['location_code']) {
				return QueueLocation::find($this->attributes['location_code'])->location_name;
			} else {
				return "-";
			}
	}

	public function getProductOnHandAttribute($value) 
	{
			return floatval($value);
	}

	public function getProductSku() 
	{
			if ($this->attributes['product_sku']) {
					return $this->attributes['product_sku'];
			} else {
					return "";
			}
	}

	public function getUnitShortname()
	{
			if ($this->unitMeasure) {
					return $this->unitMeasure->unit_shortname;
			} else {
					return "U";
			}
	}

	public function getPurchaseUnitShortname()
	{
			if ($this->purchaseUnitMeasure) {
					return $this->purchaseUnitMeasure->unit_shortname;
			} else {
					return "U";
			}
	}
}
