<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Loan extends Model
{
	protected $table = 'loans';
	protected $fillable = [
				'item_code',
				'loan_request_by',
				'ward_code',
				'type_code',
				'location_code',
				'loan_quantity',
				'loan_date_start',
				'loan_date_end',
				'loan_recur',
				'loan_description',
				'loan_closure_datetime',
				'loan_closure_description',
				'loan_is_indent',
				'exchange_id',
				'input_line_id',
				'loan_code'];
	
    protected $guarded = ['loan_id'];
    protected $primaryKey = 'loan_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'item_code'=>'required',
				'loan_request_by'=>'required',
				'loan_date_start'=>'size:10|date_format:d/m/Y',
				'loan_date_end'=>'size:10|date_format:d/m/Y',
				'loan_closure_datetime'=>'size:16|date_format:d/m/Y H:i',
				'ward_code'=>'required_without_all:location_code',
				'location_code'=>'required_without_all:ward_code',
			];
			
        	if ($method=='') {
        	    $rules['loan_code'] = 'required';
        	}
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}
	
	public function setLoanDateStartAttribute($value)
	{
		if (empty($value)) {
				$this->attributes['loan_date_start'] = null;
		}
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['loan_date_start'] = DojoUtility::dateWriteFormat($value);
		}
	}


	/**
	public function getLoanDateStartAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function getLoanDateStart()
	{
		return Carbon::createFromFormat('d/m/Y', $this->loan_date_start);
	}

	public function getLoanDateEnd()
	{
		return Carbon::createFromFormat('d/m/Y', $this->loan_date_end);
	}

	**/
	public function setLoanDateEndAttribute($value)
	{
		if (empty($value)) {
				$this->attributes['loan_date_end'] = null;
		}
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['loan_date_end'] = DojoUtility::dateWriteFormat($value);
		}
	}

	/**
	public function getLoanDateEndAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}
	**/

	public function setLoanClosureDatetimeAttribute($value)
	{
		if (empty($value)) {
				$this->attributes['loan_closure_datetime'] = null;
		}
		if (DojoUtility::validateDateTime($value)==true) {
			$this->attributes['loan_closure_datetime'] = DojoUtility::dateTimeWriteFormat($value);
		}
	}

	/**
	public function getLoanClosureDatetimeAttribute($value)
	{
		return DojoUtility::dateTimeReadFormat($value);
	}

	public function getLoanClosureDatetime()
	{
		return Carbon::createFromFormat('d/m/Y H:i', $this->loan_closure_datetime);
	}
	**/

	public function user()
	{
			return $this->belongsTo('App\User', 'loan_request_by', 'id');
	}

	public function ward()
	{
			return $this->belongsTo('App\Ward', 'ward_code');
	}

	public function status()
	{
			return $this->belongsTo('App\LoanStatus', 'loan_code');
	}

	public function getItemName($item_code = null)
	{
			
			//if (empty($product)) {
			if ($this->type_code=='folder') {
					// Return MRN
					return substr($this->item_code,0,2).'-'.substr($this->item_code,2,8).'-'.substr($this->item_code,10,4);
					//return $this->item_code;
			} else {
					$product = Product::find($this->item_code);
					return $product->product_name;
			}

	}	

	public function getItemCode($item_code = null)
	{
			$product = Product::find($item_code);
			if (empty($product)) {
					// Return MRN
					return substr($this->item_code,0,2).'-'.substr($this->item_code,2,8).'-'.substr($this->item_code,10,4);
					//return $this->item_code;
			} else {
					return $product->product_code;
			}

	}	
	public function location()
	{
			return $this->belongsTo('App\QueueLocation', 'loc','location_code');
	}

	public function type()
	{
			return $this->belongsTo('App\LoanType', 'type_code');
	}

	public function patient()
	{
			return $this->belongsTo('App\Patient', 'item_code','patient_mrn');
	}

	public function product()
	{
			return $this->belongsTo('App\Product', 'item_code','product_Code');
	}
}
