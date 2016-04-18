<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ProductSearch extends Model
{
	protected $table = 'products';
	protected $fillable = [
				'product_name',
				'category_code',
				'unit_code',
				'order_form',
				'product_upc',
				'product_unit_charge',
				'product_purchased',
				'product_sold',
				'product_sale_price',
				'product_bom',
				'product_reorder',
				'product_purchase_price',
				'location_code',
				'form_code',
				'product_average_cost',
				'product_conversion_unit',
				'product_sale_margin',
				'product_gst',
				'status_code'];
	
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
        	    $rules['product_code'] = 'required|max:20.0|unique:products';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}