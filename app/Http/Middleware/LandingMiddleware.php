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

		if ($request->user()->can('module-patient')) {
				return redirect('/patients');
		} 		

		if ($request->user()->can('module-consultation')) {
				return redirect('/patient_lists');
		} 		

		if ($request->user()->can('module-inventory')) {
				return redirect('/products');
		} 		

        return $next($request);
    }
}
