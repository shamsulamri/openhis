<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class StockReceive extends Model
{
	protected $table = 'stock_receives';
	protected $fillable = [
				'input_id',
				'store_code',
				'invoice_number',
				'delivery_number',
				'invoice_date'];
	
    protected $guarded = ['receive_id'];
    protected $primaryKey = 'receive_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'input_id'=>'required',
				'invoice_number'=>'required',
				'store_code'=>'required',
				'invoice_date'=>'size:10|date_format:d/m/Y',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
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

	public function store()
	{
			return $this->belongsTo('App\Store', 'store_code');
	}
}
