<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietTexture extends Model
{
	protected $table = 'diet_textures';
	protected $fillable = [
				'texture_code',
				'diet_code',
				'texture_name'];
	
    protected $guarded = ['texture_code'];
    protected $primaryKey = 'texture_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'diet_code'=>'required',
				'texture_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['texture_code'] = 'required|max:10|unique:diet_textures';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}