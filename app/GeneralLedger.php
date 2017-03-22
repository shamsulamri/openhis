<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class GeneralLedger extends Model
{
	protected $table = 'general_ledgers';
	protected $fillable = [
				'gl_code',
				'gl_name'];
	
    protected $guarded = ['gl_code'];
    protected $primaryKey = 'gl_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'gl_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['gl_code'] = 'required|max:20|unique:general_ledgers';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}