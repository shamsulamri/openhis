<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class InventoryBatch extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'inventory_batches';
	protected $fillable = [
				'product_code',
				'batch_number',
				'batch_expiry_date',
				'batch_description',
				'deleted_at'];
	
    protected $guarded = ['batch_id'];
    protected $primaryKey = 'batch_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'batch_number'=>'required',
				'product_code'=>'required',
				'batch_expiry_date'=>'size:10|date_format:d/m/Y',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setBatchExpiryDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['batch_expiry_date'] = DojoUtility::dateWriteFormat($value);
		} else {
			$this->attributes['batch_expiry_date'] = NULL;
		}
	}


	public function getBatchExpiryDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

}
