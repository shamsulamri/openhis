<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ProductCategoryParent extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'product_category_parents';
	protected $fillable = [
				'parent_name',
				'parent_index'];
	
    protected $guarded = ['parent_code'];
    protected $primaryKey = 'parent_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'parent_name'=>'required',
				'parent_index'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['parent_code'] = 'required|max:20.0|unique:product_category_parents';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
