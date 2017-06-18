<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class StockInput extends Model
{
	protected $table = 'stock_inputs';
	protected $fillable = [
				'store_code',
				'product_code',
				'amount_current',
				'amount_new',
				'amount_difference'];
	
    protected $guarded = ['input_id'];
    protected $primaryKey = 'input_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'product_code'=>'required',
				'amount_current'=>'required',
				'amount_new'=>'required',
				'amount_difference'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
