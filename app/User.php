<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	protected $table = 'users';
	protected $fillable = [
				'name',
				'username',
				'password'
				];
	

    protected $hidden = [
		        'password', 'remember_token',
			    ];

	public function authorization()
	{
		return $this->hasOne('App\UserAuthorization', 'author_id','author_id');
	}

	public function appointment()
	{
		return $this->hasOne('App\AppointmentService', 'user_id', 'id');
	}	
}
