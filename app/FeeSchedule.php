<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class FeeSchedule extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'fee_schedules';
	protected $fillable = [
				'part',
				'header',
				'sub_header',
				'description',
				'value',
				'value2'];
	
    protected $guarded = ['fee_code'];
    protected $primaryKey = 'fee_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'part'=>'required',
				'header'=>'required',
				'sub_header'=>'required',
				'description'=>'required',
				'value'=>'required',
				'value2'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['fee_code'] = 'required|max:20|unique:fee_schedules';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
