<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PatientClassification extends Model
{
	protected $table = 'patient_classifications';
	protected $fillable = [
				'classification_code',
				'classification_name'];
	
    protected $guarded = ['classification_code'];
    protected $primaryKey = 'classification_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'classification_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['classification_code'] = 'required|max:20|unique:patient_classifications';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
