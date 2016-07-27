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
		
		$gate->define('module-admin', function ($user) {
				return $user->authorization->system_administrator;
		});

		$gate->define('module-support', function ($user) {
				return $user->authorization->module_support;
		});
		
		$gate->define('module-discharge', function ($user) {
				return $user->authorization->module_discharge;
		});

		$gate->define('module-diet', function ($user) {
				return $user->authorization->module_diet;
		});

		$gate->define('module-medical-record', function ($user) {
				return $user->authorization->module_medical_record;
		});

		$gate->define('patient_list', function ($user) {
				return $user->authorization->patient_list;
		});

		$gate->define('product_list', function ($user) {
				return $user->authorization->product_list;
		});

		$gate->define('loan_function', function ($user) {
				return $user->authorization->loan_function;
		});

		$gate->define('appointment_function', function ($user) {
				return $user->authorization->appointment_function;
		});
    }
}
