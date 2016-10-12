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
				'employee_id',
				'author_id',
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
		return $this->hasOne('App\AppointmentService', 'user_id', 'id');
	}	
}
