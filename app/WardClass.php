<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class WardClass extends Model
{
	protected $table = 'ward_classes';
	protected $fillable = [
				'class_code',
				'class_price',
				'class_diet',
				'class_name'];
	
    protected $guarded = ['class_code'];
    protected $primaryKey = 'class_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'class_name'=>'required',
				'class_price'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['class_code'] = 'required|max:20|unique:ward_classes';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
