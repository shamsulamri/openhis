<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PurchaseOrder extends Model
{
	protected $table = 'purchase_orders';
	protected $fillable = [
				'purchase_id',
				'user_id',
				'supplier_code',
				'purchase_date',
				'purchase_reference',
				'purchase_posted',
				'purchase_received',
				'store_code',
				'purchase_description',
				'receive_datetime',
				'purchase_number',
				'invoice_number',
				'invoice_date'];
	
    protected $guarded = ['purchase_id'];
    protected $primaryKey = 'purchase_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'supplier_code'=>'required',
				'purchase_date'=>'required|date_format:d/m/Y|size:10',
				'invoice_date'=>'date|date_format:d/m/Y|size:10',
			];

			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
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

}
