<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Log;

class OrderMiddleware
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
				if ($request->user()->cannot('module-order')) {
						return redirect('/unauthorized');
				} 		
		} else {
				return redirect('/login');
		}	

        return $next($request);
    }
}
