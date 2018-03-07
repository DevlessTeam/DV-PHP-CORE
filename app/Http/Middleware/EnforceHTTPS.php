<?php

namespace App\Http\Middleware;

use Closure;

class EnforceHTTPS
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
        if (!$request->secure() && !\App::environment('local')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
