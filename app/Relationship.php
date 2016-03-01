<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Relationship extends Model
{
	protected $table = 'ref_relationships';
	protected $fillable = [
				'relation_code',
				'relation_name'];
	
    protected $guarded = ['relation_code'];
    protected $primaryKey = 'relation_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'relation_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['relation_code'] = 'required|max:20|unique:ref_relationships';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}