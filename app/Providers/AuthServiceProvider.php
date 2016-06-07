<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
	
		$gate->before(function ($user) {
				if ($user->authorization->system_administrator==1) {
						return true;
				}
		});

		$gate->define('module-patient', function ($user) {
				return $user->authorization->module_patient;
		});

		$gate->define('module-consultation', function ($user) {
				return $user->authorization->module_consultation;
		});

		$gate->define('module-inventory', function ($user) {
				return $user->authorization->module_inventory;
		});

		$gate->define('module-ward', function ($user) {
				return $user->authorization->module_ward;
		});
        //
    }
}
