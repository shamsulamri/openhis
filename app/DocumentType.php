<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DocumentType extends Model
{
	protected $table = 'document_types';
	protected $fillable = [
				'type_code',
				'type_name'];
	
    protected $guarded = ['type_code'];
    protected $primaryKey = 'type_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'type_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['type_code'] = 'required|max:20|unique:document_types';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}