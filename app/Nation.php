<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Nation extends Model
{
	protected $table = 'ref_nations';
	protected $fillable = [
				'nation_code',
				'nation_name',
				'nation_nationality'];
	
    protected $guarded = ['nation_code'];
    protected $primaryKey = 'nation_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'nation_name'=>'required',
				'nation_nationality'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['nation_code'] = 'required|max:20|unique:ref_nations';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}