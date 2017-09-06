<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ProductGroup extends Model
{
	protected $table = 'product_groups';
	protected $fillable = [
				'group_code',
				'group_name',
				'gl_code'];
	
    protected $guarded = ['group_code'];
    protected $primaryKey = 'group_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'group_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['group_code'] = 'required|max:20|unique:product_groups';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
