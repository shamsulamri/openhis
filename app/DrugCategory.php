<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DrugCategory extends Model
{
	protected $table = 'drug_categories';
	protected $fillable = [
				'category_code',
				'system_code',
				'category_name'];
	
    protected $guarded = ['category_code'];
    protected $primaryKey = 'category_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'category_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['category_code'] = 'required|max:20|unique:drug_categories';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}