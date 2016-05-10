<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\CoreController as core;

use GuzzleHttp;

class ScriptController extends Controller
{
    public function internal_services()
    {
        
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
        //payload 
        $core = new core(); 
        
        $event = [
            'method' => $payload['method'],
            'params' => $payload['params'],
            'calls'  => $payload['script']
        ];
        #if($payload['pre_set'] == 1){$core->pre_script($resource,$payload);}
        $result = eval("\$event = \$event;".$payload['script']);
        #if($payload['post_set'] == 1){$core->post_script($resource,$payload);}
        
        return $result;   
    }
}
