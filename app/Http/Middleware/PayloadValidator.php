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
      
            return $next($request);

        }

      
    }
