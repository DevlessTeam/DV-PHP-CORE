<?php

namespace App\Http\Middleware;

use DB;
use Closure;

use App\Helpers\Helper as Helper;
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

       $app_object = DB::table('apps')->first();

       $request_path = \Request::path(); 
       $app_exists = $app_object;
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
