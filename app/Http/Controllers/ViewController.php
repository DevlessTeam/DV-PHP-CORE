<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\ServiceController as Service; 

class ViewController extends Controller
{
    public function access_views(Request $request, $service_name, $resource, $template)
    {   
        $method = $request->method();
        $service = new Service();
        $payload = $service->assign_to_service($service_name, $resource, $method);
        $params = $service->get_params($method, $request);
        $payload['params'] = $params;
       
        return $this->_fetch_view($service_name, $template, $payload);
        
    }
 
    private function _fetch_view($service, $template, $payload)
    {
        
        return view('auth.index')->with($payload);
    }
    
    public function static_files(Request $request)
    {
        
        return response(file_get_contents('../resources/'.$request->path()))->header('Content-Type', 'text/css');
    }
}
