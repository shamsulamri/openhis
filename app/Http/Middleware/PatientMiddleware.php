<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PatientMiddleware
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
		if (Auth::check()) {
				if ($request->user()->cannot('module-patient')) {
						return redirect('/unauthorized');
				} 		
		} else {
				return redirect('/login');
		}	
        return $next($request);
    }
}
