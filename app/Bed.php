<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Bed extends Model
{
	protected $table = 'beds';
	protected $fillable = [
				'bed_code',
				'encounter_code',
				'class_code',
				'ward_code',
				'room_code',
				'status_code',
				'bed_name',
				'bed_virtual',
				'gender_code',
				'department_code'];
	
    protected $guarded = ['bed_code'];
    protected $primaryKey = 'bed_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'ward_code'=>'required',
				'bed_name'=>'required',
				'encounter_code'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['bed_code'] = 'required|max:20|unique:beds';
        	    $rules['encounter_code'] = 'required';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function ward()
	{
			return $this->belongsTo('App\Ward','ward_code', 'ward_code');
	}

	public function room()
	{
			return $this->belongsTo('App\Room', 'room_code', 'room_code');
	}

}
