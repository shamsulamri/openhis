<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class StockInputLine extends Model
{
	protected $table = 'stock_input_lines';
	protected $fillable = [
				'input_id',
				'product_code',
				'batch_number',
				'amount_current',
				'amount_new',
				'amount_difference'];
	
    protected $guarded = ['line_id'];
    protected $primaryKey = 'line_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'input_id'=>'required',
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

	
	public function product()
	{
		return $this->belongsTo('App\Product', 'product_code');
	}
}
