<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DrugRoute extends Model
{
	protected $table = 'drug_routes';
	protected $fillable = [
				'route_name',
				'route_label'];
	
    protected $guarded = ['route_code'];
    protected $primaryKey = 'route_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'route_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['route_code'] = 'required|max:20|unique:drug_routes';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}