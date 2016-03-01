<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietMeal extends Model
{
	protected $table = 'diet_meals';
	protected $fillable = [
				'meal_code',
				'meal_name'];
	
    protected $guarded = ['meal_code'];
    protected $primaryKey = 'meal_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'meal_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['meal_code'] = 'required|max:20|unique:diet_meals';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}