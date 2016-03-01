<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Triage extends Model
{
	protected $table = 'triages';
	protected $fillable = [
				'triage_code',
				'triage_name',
				'triage_color',
				'triage_position'];
	
    protected $guarded = ['triage_code'];
    protected $primaryKey = 'triage_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'triage_name'=>'required',
				'triage_color'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['triage_code'] = 'required|max:10.0|unique:triages';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}