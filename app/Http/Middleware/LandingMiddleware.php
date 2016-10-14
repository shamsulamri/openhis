<?php

namespace App\Http\Middleware;

use Closure;

class LandingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

		if ($request->user()->can('system-administrator')) {
				return redirect('/maintenance');
		} 		

		if ($request->user()->can('module-patient')) {
				return redirect('/patients');
		} 		

		if ($request->user()->can('module-consultation')) {
				return redirect('/patient_lists');
		} 		

		if ($request->user()->can('module-diet')) {
				return redirect('/diet_orders');
		} 		

		if ($request->user()->can('module-inventory')) {
				return redirect('/products');
		} 		

		if ($request->user()->can('module-support')) {
				return redirect('/order_queues');
		} 		

		if ($request->user()->can('module-discharge')) {
				return redirect('/discharges');
		} 		

		if ($request->user()->can('module-ward')) {
				return redirect('/admissions');
		} 		

		if ($request->user()->can('module-medical-record')) {
				return redirect('/loans?type=folder');
		} 		
        return $next($request);
    }
}
