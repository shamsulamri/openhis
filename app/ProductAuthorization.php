<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ProductAuthorization extends Model
{
	protected $table = 'product_authorizations';
	protected $fillable = [
				'author_id',
				'category_code'];
	

	public function validate($input, $method) {
			$rules = [
				'author_id'=>'required',
				'category_code'=>'required',
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

	public function category()
	{
			return $this->belongsTo('App\ProductCategory','category_code');
	}
}
