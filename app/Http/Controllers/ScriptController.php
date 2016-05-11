<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\CoreController as Core;
use App\Http\Controllers\ServiceController as Service;

use \App\Helpers\Helper as Helper;


class ScriptController extends Controller
{
    
     /*
     * Call on services from within scripts and views 
     * 
     * @param json $payload request payload
     * @param string $service_name 
     * @param string $resource 
     * @param string $method
     * @return array|object  
     */
    public static function internal_services($json_payload, $service_name, $resource, $method)
    {   
        $service = new Service();
        $request = [
        "resource" => json_decode($json_payload),
        "method" => $method
        ];
        $output = $service->resource($request, $service_name, $resource, $internal_access=true);
    }
     /*
     * script execution sandbox
     * 
     * @param string $resource name of resource belonging to a service 
     * @param array $payload request parameters
     * @return array  
     */
    public function run_script($resource,$payload)
    { 
        //available methods
        $json = '';
        $EVENT = [
            'method' => $payload['method'],
            'params' => $payload['params'],
            'script'  => $payload['script']
        ];
        $service = '$this->internal_services';
//NB: position matters here
$code = <<<EOT
$payload[script];
EOT;
        $result = eval($code);
        
        return $result;   
    }
}



