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
				'class_name'];
	
    protected $guarded = ['class_code'];
    protected $primaryKey = 'class_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'class_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['class_code'] = 'required|max:10|unique:ward_classes';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}