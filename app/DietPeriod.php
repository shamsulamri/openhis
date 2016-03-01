<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietPeriod extends Model
{
	protected $table = 'diet_periods';
	protected $fillable = [
				'period_code',
				'period_name',
				'period_position'];
	
    protected $guarded = ['period_code'];
    protected $primaryKey = 'period_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'period_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['period_code'] = 'required|max:20.0|unique:diet_periods';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}