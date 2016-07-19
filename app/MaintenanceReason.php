<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class MaintenanceReason extends Model
{
	protected $table = 'ref_maintenance_reasons';
	protected $fillable = [
				'reason_code',
				'reason_name'];
	
    protected $guarded = ['reason_code'];
    protected $primaryKey = 'reason_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'reason_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['reason_code'] = 'required|max:20|unique:ref_maintenance_reasons';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}