<?php

namespace App\Helpers;
use Session;
use App\Http\Controllers\ScriptController as script;
/* 
*@author Eddymens <eddymens@devless.io
 */
use App\Helpers\Helper as Helper;

class Sdk extends Helper
{
    //
    private $service_name, $resource, $method;
    public function __construct($service_name, $resource)
    {
        $this->service_name = $service_name;
        $this->resource = $resource;
        
    }
    
    public  function table($action)
    {
       //
        $service_name = $this->service_name;
        $resource = $this->resource;
        $method = 'DELETE';
        $json_payload = 
                '{  
            "resource":[  
               {  
                  "name":"'.$table_name.'",
                  "params":[  
                     {  
                        "drop":"true"    
                     }
                  ]
               }

             ]
         }       
         ';
        $script = new script();
        $output = $internal_service = $script->internal_services($json_payload, $service_name,
                $resource, $method);
        
        return $output;
        
    }
}


 