<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class History extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'ref_histories';
	protected $fillable = [
				'history_code',
				'history_name',
				'deleted_at'];
	
    protected $guarded = ['history_code'];
    protected $primaryKey = 'history_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'history_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['history_code'] = 'required|max:20.0|unique:ref_histories';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
