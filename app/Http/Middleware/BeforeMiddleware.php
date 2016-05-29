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
       $app_exists = DB::table('apps')->first(); 
       $request_path = \Request::path(); 
       
      if($app_exists == null && $request_path != 'setup')
      {

          return redirect('/setup');
      } 
      else if ($app_exists == null && $request_path != 'setup') 
      {
          return redirect('/');
      }

        return $next($request);
    }
}

