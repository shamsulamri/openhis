<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DrugSpecialInstruction extends Model
{
	protected $table = 'drug_special_instructions';
	protected $fillable = [
				'special_instruction_english',
				'special_instruction_bahasa'];
	
    protected $guarded = ['special_code'];
    protected $primaryKey = 'special_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [

			];

			
        	if ($method=='') {
        	    $rules['special_code'] = 'required|max:20|unique:drug_special_instructions';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}