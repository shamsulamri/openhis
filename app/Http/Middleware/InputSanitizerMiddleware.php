<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class InputSanitizerMiddleware
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
			$input = $request->input();

			Log::info("----");
			Log::info($input);
			array_walk_recursive($input, function(&$value) {
				if (empty($value)) {
					//$value = StringHelper::trimNull($value);
					$value = NULL;
				}
			});

			Log::info($input);
			$request->replace($input);

			return $next($request);
    }
}
