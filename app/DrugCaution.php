<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DrugCaution extends Model
{
	protected $table = 'drug_cautions';
	protected $fillable = [
				'caution_english',
				'caution_bahasa'];
	
    protected $guarded = ['caution_code'];
    protected $primaryKey = 'caution_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [

			];

			
        	if ($method=='') {
        	    $rules['caution_code'] = 'required|max:20|unique:drug_cautions';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}