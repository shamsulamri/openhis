<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Log;
class InventoryMiddleware
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

		/*
		if ($request->user()->authorization->module_inventory=='0') {
				return redirect('/unauthorized');
		} 		
		 */

		if (Auth::check()) {
				if ($request->user()->cannot('module-inventory')) {
						return redirect('/unauthorized');
				} 		
		} else {
				return redirect('/login');
		}	

        return $next($request);
    }
}
