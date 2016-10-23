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
				'stock_date'=>'required|size:16|date_format:d/m/Y H:i',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setStockDateAttribute($value)
	{
		if (DojoUtility::validateDateTime($value)==true) {
			$this->attributes['stock_date'] = DojoUtility::dateTimeWriteFormat($value);
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

	public function getStockDate()
	{
		return Carbon::createFromFormat('d/m/Y H:i', $this->stock_date);
	}
}
