<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ProductUom extends Model
{
	protected $table = 'product_uoms';
	protected $fillable = [
				'product_code',
				'unit_code',
				'uom_rate',
				'uom_price',
				'uom_cost'];
	

	public function validate($input, $method) {
			$rules = [
				'uom_rate'=>'required',
			];

        	if ($method=='') {
        	    $rules['unit_code'] = 'required';
        	}
			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function unitMeasure()
	{
			return $this->belongsTo('App\UnitMeasure', 'unit_code');
	}

	public function product()
	{
			return $this->belongsTo('App\Product', 'product_code');
	}
}
