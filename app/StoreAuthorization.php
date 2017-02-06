<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class StoreAuthorization extends Model
{
	protected $table = 'store_authorizations';
	protected $fillable = [
				'author_id',
				'store_code'];
	

	public function validate($input, $method) {
			$rules = [
				'author_id'=>'required',
				'store_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function authorization()
	{
		return $this->hasOne('App\UserAuthorization', 'author_id','author_id');
	}

	public function store()
	{
			return $this->belongsTo('App\Store','store_code');
	}
}
