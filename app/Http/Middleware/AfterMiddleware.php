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
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $no_header = DVL::header_required($request);
        if($no_header)
        {
            return $response;
        }
        else
        {
             return $response ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
          
        }
            
    }
}
