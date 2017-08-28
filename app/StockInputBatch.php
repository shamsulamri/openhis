<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class StockInputBatch extends Model
{
	protected $table = 'stock_input_batches';
	protected $fillable = [
				'input_id',
				'line_id',
				'product_code',
				'batch_number',
				'batch_expiry_date',
				'batch_quantity'];
	
    protected $guarded = ['batch_id'];
    protected $primaryKey = 'batch_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'line_id'=>'required',
				'product_code'=>'required',
				'batch_number'=>'required',
				'batch_expiry_date'=>'required',
				'batch_quantity'=>'required',
			];

			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	/*
	public function setBatchExpiryDateAttribute($value)
	{
		$this->attributes['batch_expiry_date'] = null;
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['batch_expiry_date'] = DojoUtility::dateWriteFormat($value);
		}
	}
	 */
	
	public function StockInputLine()
	{
			return $this->belongsTo('App\StockInputLine', 'line_id');
	}
}
