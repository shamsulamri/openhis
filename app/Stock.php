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
				'store_code_transfer',
				'product_code',
				'stock_datetime',
				'stock_quantity',
				'stock_description',
				'stock_tag',
				'username',
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
				'stock_datetime'=>'required|size:16|date_format:d/m/Y H:i',
			];

			
			
			$messages = [
				'required' => 'This field is required',
				'stock_datetime.date_format' => 'Invalid date or format'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setStockDatetimeAttribute($value)
	{
		if (DojoUtility::validateDateTime($value)==true) {
			$this->attributes['stock_datetime'] = DojoUtility::dateTimeWriteFormat($value);
		}
	}


	public function getStockDateAttribute($value)
	{
		return DojoUtility::dateTimeReadFormat($value);
	}

	public function product()
	{
		return $this->belongsTo('App\Product', 'product_code');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'username','username');
	}
	
	/*
	public function getStockDateTime()
	{
		return Carbon::createFromFormat('d/m/Y H:i', $this->stock_datetime);
	}
	 */
}
