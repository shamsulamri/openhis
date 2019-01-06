<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PurchaseRequestStatus extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'purchase_request_statuses';
	protected $fillable = [
				'status_code',
				'status_name'];
	
    protected $guarded = ['status_code'];
    protected $primaryKey = 'status_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'status_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['status_code'] = 'required|max:20|unique:purchase_request_statuses';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
