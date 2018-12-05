<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class PurchaseOrderLine extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'purchase_order_lines';
	protected $fillable = [
				'purchase_id',
				'product_code',
				'unit_code',
				'uom_rate',
				'line_quantity',
				'line_unit_price',
				'tax_code',
				'tax_rate',
				'line_subtotal',
				'line_subtotal_tax',
		];
	
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
		$this->attributes['line_expiry_date'] = null;
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['line_expiry_date'] = DojoUtility::dateWriteFormat($value);
		}
	}

	/**
	public function getLineExpiryDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}
	**/

	public function setLineReceiveDate1Attribute($value)
	{
		$this->attributes['line_receive_date_1'] = null;
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['line_receive_date_1'] = DojoUtility::dateWriteFormat($value);
		}
	}

	/**
	public function getLineReceiveDate1Attribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}
	**/

	public function setLineReceiveDate2Attribute($value)
	{
		$this->attributes['line_receive_date_2'] = null;
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['line_receive_date_2'] = DojoUtility::dateWriteFormat($value);
		}
	}

	/**
	public function getLineReceiveDate2Attribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}
	**/

	public function purchaseOrder()
	{
		return $this->belongsTo('App\PurchaseOrder','purchase_id');
	}

	public function product()
	{
		return $this->belongsTo('App\Product','product_code');
	}

	public function tax()
	{
		return $this->belongsTo('App\TaxCode','tax_code');
	}

	public function uom()
	{
		return $this->belongsTo('App\UnitMeasure','unit_code');
	}
}
