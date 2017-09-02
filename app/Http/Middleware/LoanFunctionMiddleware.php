<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LoanFunctionMiddleware
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
				if ($request->user()->cannot('loan_function')) {
						return redirect('/unauthorized');
				} 		
		} else {
				return redirect('/login');
		}	
        return $next($request);
    }
}
