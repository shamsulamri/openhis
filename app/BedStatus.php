<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BedStatus extends Model
{
	protected $table = 'bed_statuses';
	protected $fillable = [
				'status_code',
				'status_name',
				'status_hidden'];
	
    protected $guarded = ['status_code'];
    protected $primaryKey = 'status_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'status_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['status_code'] = 'required|max:10.0|unique:bed_statuses';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}