<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use App\ProductUom;
use Log;
class BillMaterial extends Model
{
	protected $table = 'bill_materials';
	protected $fillable = [
				'product_code',
				'bom_product_code',
				'unit_code',
				'bom_quantity'];
	

	public function validate($input, $method) {
			Log::info($method);
			$rules = null;
			
			if ($method=='PUT') {
					$rules = [
						'bom_quantity'=>'required',
					];
			} else {
					$rules = [
						'product_code'=>'required',
						'bom_product_code'=>'required',
						'bom_quantity'=>'required',
					];
			}
			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function product()
	{
			return $this->belongsTo('App\Product', 'bom_product_code','product_code');
	}

	public function unit() 
	{
			return $this->belongsTo('App\UnitMeasure', 'unit_code');
	}

	public function unitPrice($product_code, $unit_code)
	{
			if (empty($unit_code)) $unit_code = 'unit';

			$unit_price = ProductUom::where('product_code','=',  $product_code)
							->where('unit_code', '=', $unit_code)
							->first();

			if (!empty($unit_price)) {
					return $unit_price->uom_price?:0;
			} else {
					return 0;
			}

	}
	
}
