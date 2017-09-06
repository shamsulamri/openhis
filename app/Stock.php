<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class Stock extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'stocks';
	protected $fillable = [
				'line_id',
				'order_id',
				'move_code',
				'store_code',
				'store_code_transfer',
				'product_code',
				'stock_datetime',
				'stock_quantity',
				'stock_value',
				'stock_description',
				'stock_tag',
				'loan_id',
				'batch_number',
				'delivery_number',
				'invoice_number',
				'expiry_date',
				'username',
				];
	
    protected $guarded = ['stock_id'];
    protected $primaryKey = 'stock_id';
    public $incrementing = true;
    
	public function validate($input, $method) {
			$product = Product::find($input['product_code']);
			$rules = [
				'move_code'=>'required',
				'store_code'=>'required',
				'product_code'=>'required',
				'stock_quantity'=>'required',
				'stock_value'=>'required',
				'store_code_transfer'=>'required_if:move_code,==,transfer',
				'expiry_date'=>'size:10|date_format:d/m/Y',
			];

			$messages = [
				'required' => 'This field is required',
				'stock_datetime.date_format' => 'Invalid date or format'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setExpiryDateAttribute($value)
	{
		$this->attributes['expiry_date'] = null;
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['expiry_date'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function setStockDatetimeAttribute($value)
	{
		if (DojoUtility::validateDateTime($value)==true) {
			$this->attributes['stock_datetime'] = DojoUtility::dateTimeWriteFormat($value);
		}
	}


	/**
	public function getStockDateAttribute($value)
	{
		return DojoUtility::dateTimeReadFormat($value);
	}
	**/

	public function product()
	{
		return $this->belongsTo('App\Product', 'product_code');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'username','username');
	}
	
	public function store()
	{
		return $this->belongsTo('App\Store', 'store_code');
	}

	public function storeTransfer()
	{
		return $this->belongsTo('App\Store', 'store_code_transfer');
	}

	public function movement()
	{
		return $this->belongsTo('App\StockMovement', 'move_code');
	}
	/*
	public function getStockDateTime()
	{
		return Carbon::createFromFormat('d/m/Y H:i', $this->stock_datetime);
	}
	 */
	public function getStockQuantity() 
	{
		if ($this->move_code == 'adjust') {
				return $this->stock_quantity;
		} else {
				return abs($this->stock_quantity);
		}
	}

	public function purchaseOrderLine()
	{
		return $this->belongsTo('App\PurchaseOrderLine', 'line_id');
	}
}
