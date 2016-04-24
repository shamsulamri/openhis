<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class OrderSet extends Model
{
	protected $table = 'order_sets';
	protected $fillable = [
				'set_code',
				'product_code'];
	
    protected $primaryKey = 'id';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'product_code'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['set_code'] = 'required|max:20';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function product()
	{
			return $this->belongsTo('App\Product','product_code');
	}	
}
