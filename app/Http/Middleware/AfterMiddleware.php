<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\DevlessHelper as DVL;
class AfterMiddleware
{
    /**
     * Handle an outgoing  result.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * 
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
       
             return $response;
        
            
    }
}
