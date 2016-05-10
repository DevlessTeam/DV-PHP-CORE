<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CoreController extends Controller
{
 
    /*
     * script to be run before any other execution 
     * 
     * @param string $resource name of resource belonging to a service 
     * @param array $payload request parameters
     * @return boolean 
     */
    public function pre_script($resource, $payload)
    {
        //
        return true;
        
    }
    
     /*
     * script to be run after any other execution 
     * 
     * @param string $resource name of resource belonging to a service 
     * @param array $payload request parameters
     * @return boolean 
     */
    public function post_script($resource, $payload)
    {
        //
        return false;
        
    }
}
