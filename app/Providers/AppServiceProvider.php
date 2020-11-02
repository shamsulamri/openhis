<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use DB;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
		/*
		DB::listen(function ($query) {
				Log::info($query->sql);
				Log::info($query->bindings);
				Log::info($query->time);
		});
		 */
		if (env('APP_ENV') === 'production') {
			$this->app['request']->server->set('HTTPS','on');
		}

		Validator::extend('greater_than_or_equal', function($attribute, $value, $parameters, $validator) {
				if ($value==$parameters[1]) {
						return True;
				}
				if ($value<$parameters[1]) {
						return False;
				} else {
						return True;
				}	
		});

		Validator::extend('lower_than_or_equal', function($attribute, $value, $parameters, $validator) {
				if ($value==$parameters[1]) {
						return True;
				}
				if ($value<$parameters[1]) {
						return True;
				} else {
						return False;
				}	
		});
	}

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
