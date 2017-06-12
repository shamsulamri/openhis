<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Log;

class User extends Authenticatable
{
	protected $table = 'users';
	protected $fillable = [
				'name',
				'username',
				'password',
				'email',
				'consultant',
				'tax_code',
				'gst_number',
				'employee_id',
				'author_id',
				'service_id',
				];
	
    protected $hidden = [
		        'password', 'remember_token',
			    ];

	public function validate($input, $method) {
			$rules = [
				'email'=>'email|required|unique:users',
				'name'=>'required',
				'username'=>'required|unique:users',
				'employee_id'=>'required|unique:users',
			];

        	if ($method=='PUT') {
					$rules = [
						'name'=>'required',
						'email'=>'required',
					];
        	}

			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function authorization()
	{
		return $this->hasOne('App\UserAuthorization', 'author_id','author_id');
	}

	public function appointment()
	{
		//return $this->hasOne('App\AppointmentService', 'user_id', 'id');
		return $this->hasOne('App\AppointmentService', 'service_id', 'service_id');
	}	
}
