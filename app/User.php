<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class User extends Authenticatable
{
	protected $table = 'users';
	protected $fillable = [
				'name',
				'username',
				'password',
				'email',
				];
	

    protected $hidden = [
		        'password', 'remember_token',
			    ];

	public function validate($input, $method) {
			$rules = [
				'email'=>'required',
				'name'=>'required',
			];

			
        	if ($method=='PUT') {

			} else {
        	    $rules['email'] = 'email|unique:users';
        	    $rules['username'] = 'required';
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
