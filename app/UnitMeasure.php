<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class UnitMeasure extends Model
{
	protected $table = 'ref_unit_measures';
	protected $fillable = [
				'unit_code',
				'unit_name',
				'unit_shortname',
				'unit_is_decimal',
				'unit_drug'];
	
    protected $guarded = ['unit_code'];
    protected $primaryKey = 'unit_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'unit_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['unit_code'] = 'required|max:10.0|unique:ref_unit_measures';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function getUnitShortnameAttribute($value)
	{
			if (empty($value)) {
					return "-";
			} else {
					return $value;
			}	
	}	

	
}
