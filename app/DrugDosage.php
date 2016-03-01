<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DrugDosage extends Model
{
	protected $table = 'drug_dosages';
	protected $fillable = [
				'dosage_name',
				'dosage_label'];
	
    protected $guarded = ['dosage_code'];
    protected $primaryKey = 'dosage_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'dosage_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['dosage_code'] = 'required|max:20|unique:drug_dosages';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}