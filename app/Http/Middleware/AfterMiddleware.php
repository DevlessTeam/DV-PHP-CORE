<?php

namespace App\Http\Middleware;

use Closure;

class AfterMiddleware
{
    /**
     * Handle an outgoing  result.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
           $response = $next($request);
        
        // Perform action
        echo 'this is the after processor'; 
        dd($response);
        return $response;
            
    }
}
