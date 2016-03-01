<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class FormPosition extends Model
{
	protected $table = 'form_positions';
	protected $fillable = [
				'form_code',
				'property_code',
				'property_position'];
	

	public function validate($input, $method) {
			$rules = [
				'form_code'=>'required',
				'property_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}
	
	public function form()
	{
			return $this->hasOne('App\Form','form_code','form_code');
	}

}
