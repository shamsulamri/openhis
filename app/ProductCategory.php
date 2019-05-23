<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ProductCategory extends Model
{
	protected $table = 'product_categories';
	protected $fillable = [
				'category_name',
				'category_price',
				'category_is_consultation',
				'group_code',
				'gl_code',
		];
	
    protected $guarded = ['category_code'];
    protected $primaryKey = 'category_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'category_name'=>'required',
			];
			
        	if ($method=='') {
        	    $rules['category_code'] = 'required|max:20|unique:product_categories';
        	}
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function getGLName()
	{
			if ($this->attributes['gl_code']) {
				return GeneralLedger::find($this->attributes['gl_code'])->gl_name;
			} else {
				return "";
			}

	}
	
}
