<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ProductMaintenance extends Model
{
	protected $table = 'product_maintenances';
	protected $fillable = [
				'product_code',
				'reason_code',
				'maintain_description',
				'maintain_datetime',
				'user_id'];
	
    protected $guarded = ['maintain_id'];
    protected $primaryKey = 'maintain_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'product_code'=>'required',
				'reason_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function setMaintainDatetimeAttribute($value)
	{
		if (DojoUtility::validateDateTime($value)==true) {
			$this->attributes['maintain_datetime'] = DojoUtility::dateTimeWriteFormat($value);
		}
	}

	public function getMaintainDatetimeAttribute($value)
	{
		return DojoUtility::dateTimeReadFormat($value);
	}
	
}
