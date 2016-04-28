<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;

class PayloadValidator 
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
      
        # get server payload 
        $server_params = (array)$request->server;
        foreach($server_params as $server_param){
            $request_method = $server_param['REQUEST_METHOD'];
        }
        if($request_method == 'POST' or $request_method == 'PUT')
            if(isset($request['resource'])){ 
                
                #control flow 
                #echo "everything went through is the only reason i am here";
            }
            else
            {    
                 $stack = 400;   
                 Helper::interrupt($stack);

            }   
            return $next($request);

        }

      
    }
