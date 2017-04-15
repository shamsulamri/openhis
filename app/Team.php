<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Team extends Model
{
	protected $table = 'teams';
	protected $fillable = [
				'team_name'];
	
    protected $guarded = ['team_code'];
    protected $primaryKey = 'team_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'team_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['team_code'] = 'required|max:20|unique:teams';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}