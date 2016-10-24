<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Product extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'products';
	protected $fillable = [
				'product_name',
				'category_code',
				'unit_code',
				'order_form',
				'product_upc',
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

	public function gst()
	{
			return $this->belongsTo('App\TaxCode', 'tax_code');
	}

	public function getProductOnHandAttribute($value) 
	{
			return str_replace('.00','',$value);
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
