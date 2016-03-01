<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BillMaterial extends Model
{
	protected $table = 'bill_materials';
	protected $fillable = [
				'product_code',
				'bom_product_code',
				'bom_quantity'];
	

	public function validate($input, $method) {
			$rules = [
				'product_code'=>'required',
				'bom_product_code'=>'required',
				'bom_quantity'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}