<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class PurchaseOrder extends Model
{
	protected $table = 'purchase_orders';
	protected $fillable = [
				'purchase_id',
				'author_id',
				'supplier_code',
				'purchase_date',
				'purchase_posted',
				'purchase_received',
				'store_code',
				'purchase_description',
				'receive_datetime',
				'invoice_number',
				'invoice_date'];
	
    protected $guarded = ['purchase_id'];
    protected $primaryKey = 'purchase_id';
    public $incrementing = true;

	
	public function validate($input, $method) {
			$rules = [
				'supplier_code'=>'required',
				'purchase_date'=>'required|date_format:d/m/Y|size:10',
				'invoice_date'=>'date_format:d/m/Y|size:10',
				'receive_datetime'=>'size:16|date_format:d/m/Y H:i',
			];

        	if ($method!='') {
        	    $rules['store_code'] = 'required';
        	}

			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function getReceiveDatetime()
	{
		return Carbon::createFromFormat('d/m/Y H:i', $this->receive_datetime);
	}

	public function setPurchaseDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['purchase_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getPurchaseDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function setInvoiceDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['invoice_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getInvoiceDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function setReceiveDatetimeAttribute($value)
	{
		if (DojoUtility::validateDateTime($value)==true) {
			$this->attributes['receive_datetime'] = DojoUtility::dateTimeWriteFormat($value);
		}
	}

	public function getReceiveDatetimeAttribute($value)
	{
		return DojoUtility::dateTimeReadFormat($value);
	}
	
	public function supplier()
	{
		return $this->belongsTo('App\Supplier', 'supplier_code');
	}

	public function store()
	{
			return $this->belongsTo('App\Store', 'store_code');
	}
}
