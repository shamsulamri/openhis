<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Stock extends Model
{
	protected $table = 'stocks';
	protected $fillable = [
				'line_id',
				'move_code',
				'store_code',
				'product_code',
				'stock_date',
				'stock_quantity',
				'stock_description',
				];
	
    protected $guarded = ['stock_id'];
    protected $primaryKey = 'stock_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'move_code'=>'required',
				'store_code'=>'required',
				'product_code'=>'required',
				'stock_quantity'=>'required',
				'stock_date'=>'required|size:10|date_format:d/m/Y',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setStockDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['stock_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getStockDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function product()
	{
		return $this->belongsTo('App\Product', 'product_code');
	}
}
