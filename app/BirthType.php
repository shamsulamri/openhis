<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BirthType extends Model
{
	protected $table = 'ref_birth_types';
	protected $fillable = [
				'birth_name'];
	
    protected $guarded = ['birth_code'];
    protected $primaryKey = 'birth_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'birth_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['birth_code'] = 'required|max:20|unique:ref_birth_types';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}