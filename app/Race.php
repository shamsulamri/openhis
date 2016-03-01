<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Race extends Model
{
	protected $table = 'ref_races';
	protected $fillable = [
				'race_code',
				'race_name'];
	
    protected $guarded = ['race_code'];
    protected $primaryKey = 'race_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'race_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['race_code'] = 'required|max:20|unique:ref_races';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}