<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietClass extends Model
{
	protected $table = 'diet_classes';
	protected $fillable = [
				'class_name',
				'class_position',
				'diet_code'];
	
    protected $guarded = ['class_code'];
    protected $primaryKey = 'class_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'class_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['class_code'] = 'required|max:20.0|unique:diet_classes';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function diet()
	{
			return $this->belongsTo('App\Diet', 'diet_code');
	}
	
}
