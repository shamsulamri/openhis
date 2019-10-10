<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class OrderImaging extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'order_imaging';
	protected $fillable = [
				'side',
				'region',
				'view'];
	
    protected $guarded = ['product_code'];
    protected $primaryKey = 'product_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'side'=>'required',
				'region'=>'required',
				'view'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['product_code'] = 'required|max:20|unique:order_imaging';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
