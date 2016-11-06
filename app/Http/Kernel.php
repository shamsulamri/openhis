<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ],

        'consultation' => [
			\App\Http\Middleware\ConsultationMiddleware::class,
        ],

        'patient' => [
			\App\Http\Middleware\PatientMiddleware::class,
        ],

        'inventory' => [
			\App\Http\Middleware\InventoryMiddleware::class,
        ],

        'ward' => [
			\App\Http\Middleware\WardMiddleware::class,
        ],

        'support' => [
			\App\Http\Middleware\SupportMiddleware::class,
        ],

        'discharge' => [
			\App\Http\Middleware\DischargeMiddleware::class,
        ],
		
        'admin' => [
			\App\Http\Middleware\AdminMiddleware::class,
        ],

        'landing' => [
			\App\Http\Middleware\LandingMiddleware::class,
        ],
        'diet_middleware' => [
			\App\Http\Middleware\DietMiddleware::class,
        ],
        'medical_record' => [
			\App\Http\Middleware\MedicalRecordMiddleware::class,
        ],
        'patient_list_middleware' => [
			\App\Http\Middleware\PatientListMiddleware::class,
        ],
        'product_list_middleware' => [
			\App\Http\Middleware\ProductListMiddleware::class,
        ],
        'loan_function_middleware' => [
			\App\Http\Middleware\LoanFunctionMiddleware::class,
        ],
        'appointment_function_middleware' => [
			\App\Http\Middleware\AppointmentFunctionMiddleware::class,
        ],
        'input_sanitizer_middleware' => [
			\App\Http\Middleware\InputSanitizerMiddleware::class,
        ],
        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];
}
