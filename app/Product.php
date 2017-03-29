<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

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
				'product_stocked',
				'product_sold',
				'product_dismantle_material',
				'product_sale_price',
				'product_bom',
				'product_reorder',
				'product_purchase_price',
				'location_code',
				'form_code',
				'gl_code',
				'product_average_cost',
				'product_conversion_unit',
				'product_conversion_code',
				'product_sale_margin',
				'product_on_hand',
				'tax_code'];
	
    	protected $guarded = ['product_code'];
    	protected $primaryKey = 'product_code';
    	public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'product_name'=>'required',
				'category_code'=>'required',
				'order_form'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['product_code'] = 'required|max:20.0|unique:products|alpha_dash';
        	}
        
			
			$messages = [
					'required' => 'This field is required',
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

	public function category()
	{
			return $this->belongsTo('App\ProductCategory','category_code');
	}

	public function unitMeasure()
	{
			return $this->belongsTo('App\UnitMeasure', 'unit_code');
	}

	public function tax()
	{
			return $this->belongsTo('App\TaxCode', 'tax_code');
	}

	public function getProductOnHandAttribute($value) 
	{
			return floatval($value);
	}

	public function getUnitShortname()
	{
			if ($this->unitMeasure) {
					return $this->unitMeasure->unit_shortname;
			} else {
					return "unit";
			}
	}
}
