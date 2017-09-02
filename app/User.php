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

	public function storeList()
	{
			$stores = StoreAuthorization::where('author_id', $this->author_id)
							->leftjoin('stores as b', 'b.store_code','=', 'store_authorizations.store_code')
							->orderBy('store_name')
							->lists('store_name', 'b.store_code')
							->prepend('','');

			if (count($stores)==1) {
				$stores = Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('','');
			}

			return $stores;
	}

	public function storeCodes()
	{
			$stores = StoreAuthorization::where('author_id', $this->author_id)->get();

			if (count($stores)==0) {
				$stores = Store::all();
			}

			return $stores->pluck('store_code');
	}

	public function categoryList()
	{
			$product_authorization = ProductAuthorization::select('category_code')->where('author_id', $this->author_id);
			$categories = ProductCategory::whereIn('category_code', $product_authorization->pluck('category_code'))->lists('category_name','category_code');
			if (count($categories)==0) {
					$categories = ProductCategory::orderBy('category_name')->lists('category_name', 'category_code');
			}

			$categories = $categories->prepend('','');

			return $categories;
	} 

	public function categoryCodes()
	{
			$codes = ProductAuthorization::select('category_code')->where('author_id', $this->author_id)
					->pluck('category_code');
			return $codes;
	}
}
