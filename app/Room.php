<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Room extends Model
{
	protected $table = 'ward_rooms';
	protected $fillable = [
				'room_code',
				'room_name'];
	
    protected $guarded = ['room_code'];
    protected $primaryKey = 'room_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'room_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['room_code'] = 'required|max:10|unique:ward_rooms';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}