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
       
       //check token and keys
       $is_key_right = ($request->header('Devless-key') == $app_object->api_key)?true : false;
       $is_key_token = ($request->header('devless-token') == $app_object->token )? true : false;
       $is_admin = Helper::is_admin_login();
       
       (($is_key_right && $is_key_token) || $is_admin)? true : Helper::interrupt(631);
       
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

