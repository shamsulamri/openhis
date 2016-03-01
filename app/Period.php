<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Period extends Model
{
	protected $table = 'ref_periods';
	protected $fillable = [
				'period_name',
				'period_label',
				'period_value'];
	
    protected $guarded = ['period_code'];
    protected $primaryKey = 'period_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'period_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['period_code'] = 'required|max:10.0|unique:ref_periods';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}