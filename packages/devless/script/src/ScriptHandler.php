<?php

namespace Devless\Script;

use App\Helpers\Messenger as messenger;
use App\Http\Controllers\ServiceController as Service;
use Devless\RulesEngine\Rules;

class ScriptHandler
{
    /**
     * Call on services from within scripts and views.
     *
     * @param $json_payload
     * @param string $service_name
     * @param string $resource
     * @param string $method
     *
     * @return array|object
     *
     * @internal param json $payload request payload
     */
    public function internal_services($json_payload, $service_name, $resource, $method)
    {
        $json_payload = json_decode($json_payload, true);
        $service = new Service();
        //prepare request payload
        $request = [
            'resource' => $json_payload['resource'],
            'method' => $method,
        ];

        session()->put('script_call', 'true');
        $service->resource($request, $service_name, $resource, $internal_access = true);
        session()->forget('script_call');

        return json_decode(json_encode(messenger::message(), true), true);
    }

    /**
     * script execution sandbox.
     *
     * @param $Dvresource
     * @param array $payload request parameters
     * @return array
     * @internal param string $resource name of resource belonging to a service
     */
    public function run_script($Dvresource, $payload)
    {
        
        $service = new Service();
        $rules = new Rules();
        $rules->requestType($payload['method']);
        $user_cred['id'] = (isset($user_cred['id']))? $user_cred['id'] :'';
        $user_cred['token'] = (isset($user_cred['token']))? $user_cred['token'] :'';
        //available internal params
        $EVENT = [
            'method' => $payload['method'],
            'params' => '',
            'script' => $payload['script'],
            'user_id' => $user_cred['id'],
             'user_token' => $user_cred['token'],
            'requestType' => $Dvresource,
        ];

        $EVENT['params'] = (isset($payload['params'][0]['field'])) ? $payload['params'][0]['field'][0] : [];

//NB: position matters here
        $code = <<<EOT
$payload[script];
EOT;
         $_____service_name = $payload['service_name'];
         $_____init_vars = $payload['script_init_vars'];
         $exec = function () use ($code, $rules, $EVENT, $_____service_name, $_____init_vars) {
                //store script params temorally
                $_____midRules = $rules;
                $_____mindEvent = $EVENT;
               $declarationString = '';
               //get declared vars
               $declarationString = $_____init_vars ;
               eval($declarationString);
               //restore script params
               $rules = $_____midRules;
               $EVENT = $_____mindEvent;
               extract($EVENT['params'], EXTR_PREFIX_ALL, 'input');
               eval($code);
         };
        
        ob_start();
        $output = $exec();
        ob_end_clean();
        
        $results['payload'] = $payload;
        $results['resource'] = $Dvresource;
        
        return $results;
    }
}
