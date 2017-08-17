<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DrugInstruction extends Model
{
	protected $table = 'drug_instructions';
	protected $fillable = [
				'instruction_english',
				'instruction_bahasa'];
	
    protected $guarded = ['instruction_code'];
    protected $primaryKey = 'instruction_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [

			];

			
        	if ($method=='') {
        	    $rules['instruction_code'] = 'required|max:20|unique:drug_instructions';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}