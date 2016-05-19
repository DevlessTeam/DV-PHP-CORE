<?php

namespace App\Http\Middleware;

use DB;
use Closure;

class BeforeMiddleware
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

      if (DB::table('apps')->first() == null && \Request::path() != 'setup') {

          return redirect('/setup');

        } elseif (DB::table('apps')->first() && \Request::path() == 'setup') {
          return redirect('/');
        }

        return $next($request);
    }
}
