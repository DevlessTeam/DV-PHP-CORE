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
    
        
       try
       {
           $app_object = DB::table('apps')->first();
           $request_path = \Request::path();
           
           if($app_object !== null)
           {
               if(isset($app_object->api_key ,$app_object->token))
                {
                    $request['devless_key'] = $app_object->api_key;
                    $request['devless_token'] = $app_object->token;
                    
                }
                else
                {
                    Helper::interrupt(631,null,[],true);
                    return;
                }
           }
           
           
       }
       catch(Exception $ex)
       {
           $app_object = null;
       }
       
       
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
