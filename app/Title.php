<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Title extends Model
{
	protected $table = 'ref_titles';
	protected $fillable = [
				'title_code',
				'title_name'];
	
    protected $guarded = ['title_code'];
    protected $primaryKey = 'title_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'title_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['title_code'] = 'required|max:20|unique:ref_titles';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}