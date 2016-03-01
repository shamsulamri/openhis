<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Store extends Model
{
	protected $table = 'stores';
	protected $fillable = [
				'store_code',
				'store_name',
				'store_receiving'];
	
    	protected $guarded = ['store_code'];
    	protected $primaryKey = 'store_code';
    	public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'store_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['store_code'] = 'required|max:20.0|unique:stores';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}