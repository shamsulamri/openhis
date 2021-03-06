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
				'license_number',
				'username',
				'password',
				'email',
				'consultant',
				'department_code',
				'tax_code',
				'tax_number',
				'employee_id',
				'author_id',
				'service_id',
				'location_code',
				];
	
    protected $hidden = [
		        'password', 'remember_token',
			    ];

	public function validate($input, $method) {
			$rules = [
				'email'=>'email|required|unique:users',
				'name'=>'required',
				'author_id'=>'required',
				'username'=>'required|unique:users',
			];

			//'department_code'=>'required_if:consultant,==,"1"'
			//'employee_id'=>'required|unique:users',
        	if ($method=='PUT') {
					$rules = [
						'name'=>'required',
						'email'=>'required',
						'department_code'=>'required_if:consultant,==,"1"'
					];
        	}

			$messages = [
				'required' => 'This field is required',
				'department_code.required_if' => 'Department required when consultant.',
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
							->lists('store_name', 'b.store_code');

			if (count($stores)==0) {
				$stores = Store::all()->sortBy('store_name')->lists('store_name', 'store_code');
			} 

			//$stores = $stores->prepend('All Store','all')->prepend('','');
			return $stores;
	}

	public function authorizedStores()
	{
			$stores = StoreAuthorization::where('author_id', $this->author_id)->get();

			if (count($stores)==0) {
				$stores = Store::all();
			}

			return $stores->pluck('store_code');
	}

	public function storeCodeInString() 
	{

			$auth_stores = StoreAuthorization::where('author_id', $this->author_id)->get();

			if (count($auth_stores)==0) {
				$auth_stores = Store::all();
			}

			$stores = "";
			foreach ($auth_stores->pluck('store_code') as $code) {
				$stores = $stores . "'" . $code . "', ";
			}

			$stores = substr($stores, 0, strlen($stores)-2);
			return $stores;
	}

	public function categoryList()
	{
			$product_authorization = ProductAuthorization::select('category_code')->where('author_id', $this->author_id);
			$categories = ProductCategory::whereIn('category_code', $product_authorization->pluck('category_code'))
					->orderBy('category_name')
					->lists('category_name','category_code');
			if (count($categories)==0) {
					$categories = ProductCategory::orderBy('category_name')
							->lists('category_name', 'category_code');
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

	public function defaultLocation($request = null) 
	{
			$location_code = null;
			if ($this->authorization->location_code) {
				$location_code = $this->authorization->location_code;
			} else {
				$location_code = $request->cookie('queue_location');
			}

			return $location_code;
	}

	public function defaultStore($request = null) {
			$store_code = null;

			if ($request) {
					if ($this->authorization->module_ward == 1) {
							$ward_code = $request->cookie('ward');
							$ward = Ward::where('ward_code', $ward_code)->first();
							if ($ward) {
									$store_code = $ward->store_code;
							}
					} else {
							$store_code = $this->authorization->store_code;
					}

					/** Overiding Store **/
					/** Example: ed, procedure room **/
					if (!empty($request->cookie('store'))) {
							$store_code = $request->cookie('store');
					}
			} else {
					if ($this->authorization->store_code) {
						$store_code = $this->authorization->store_code;
					}
			}

			return $store_code;
	}

}
