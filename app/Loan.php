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
				'loan_quantity',
				'loan_date_start',
				'loan_date_end',
				'loan_recur',
				'loan_description',
				'loan_return',
				'loan_return_description',
				'loan_is_folder',
				'loan_code'];
	
    protected $guarded = ['loan_id'];
    protected $primaryKey = 'loan_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'item_code'=>'required',
				'loan_request_by'=>'required',
				'ward_code'=>'required',
				'loan_date_start'=>'size:10|date_format:d/m/Y',
				'loan_date_end'=>'size:10|date_format:d/m/Y',
				'loan_return'=>'size:16|date_format:d/m/Y H:i',
				'loan_code'=>'required',
			];

			
			
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

	public function setLoanDateEndAttribute($value)
	{
		if (empty($value)) {
				$this->attributes['loan_date_end'] = null;
		}
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['loan_date_end'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getLoanDateEndAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function setLoanReturnAttribute($value)
	{
		if (empty($value)) {
				$this->attributes['loan_return'] = null;
		}
		if (DojoUtility::validateDateTime($value)==true) {
			$this->attributes['loan_return'] = DojoUtility::dateTimeWriteFormat($value);
		}
	}

	public function getLoanReturnAttribute($value)
	{
		return DojoUtility::dateTimeReadFormat($value);
	}

	public function getLoanReturn()
	{
		return Carbon::createFromFormat('d/m/Y H:i', $this->loan_return);
	}

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

	public function getItemName()
	{
			$product = Product::find($this->item_code);
			if (empty($product)) {
					return $this->item_code;
			} else {
					return $product->product_name;
			}

	}	

}
