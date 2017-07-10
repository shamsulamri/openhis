<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class StockStore extends Model
{
	protected $table = 'stock_stores';
	protected $fillable = [
				'store_code',
				'product_code',
				'stock_quantity',
				];
	

	public function validate($input, $method) {
			$rules = [
				'store_code'=>'required',
				'product_code'=>'required',
				'stock_quantity'=>'required',
			];
			
			$messages = [
				'required' => 'This field is required',
			];
			
			return validator::make($input, $rules ,$messages);
	}

}
