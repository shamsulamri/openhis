<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PurchaseOrderLine extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'purchase_order_lines';
	protected $fillable = [
				'purchase_id',
				'product_code',
				'line_quantity_ordered',
				'line_quantity_received',
				'line_price',
				'line_batch_number',
				'line_expiry_date',
				'line_total',
				'line_quantity_received_2'];
	
    protected $guarded = ['line_id'];
    protected $primaryKey = 'line_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'purchase_id'=>'required',
				'line_expiry_date'=>'size:10|date_format:d/m/Y',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setLineExpiryDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['line_expiry_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getLineExpiryDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function purchaseOrder()
	{
		return $this->belongsTo('App\PurchaseOrder','purchase_id');
	}

	public function product()
	{
		return $this->belongsTo('App\Product','product_code');
	}
}
