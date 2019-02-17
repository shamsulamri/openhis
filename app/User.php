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
				'username'=>'required|unique:users',
				'employee_id'=>'required|unique:users',
				'department_code'=>'required_if:consultant,==,"1"'
			];

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

	public function defaultStore($request = null)
	{
			$default_store=null;
			$store = null;
			if ($this->authorization->store_code) {
				$default_store = $this->authorization->store_code;
			} else {
				/*
				if (empty($request)) {
					if ($this->authorization->module_inventory==1) {
							$ward_code = $request->cookie('ward');
							$ward = Ward::find($ward_code);
							if ($ward) {
								$default_store = $ward->store_code;
							} 
					} 		
				} 		
				 */
				if (!empty($request)) {
					if (!empty($request->cookie('store'))) {
							$default_store = $request->cookie('store');
					}
				}

				if ($default_store == null) {
						$default_store = $this->authorizedStores()[0];
				}
			}
			return $default_store;
	}
}
