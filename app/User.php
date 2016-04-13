<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


	public function authorization()
	{
		return $this->hasOne('App\UserAuthorization', 'id','id');
	}

	public function appointment()
	{
			return $this->hasOne('App\AppointmentService', 'user_id', 'id');
	}
}
