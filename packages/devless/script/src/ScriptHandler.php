<?php

namespace Devless\Script;

use App\Helpers\Helper as Helper;
use App\Helpers\Messenger as messenger;
use App\Http\Controllers\ServiceController as Service;

class ScriptHandler
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
    public function internal_services($json_payload, $service_name, $resource, $method)
    {
        $json_payload = json_decode($json_payload, true);
        $service = new Service();

        //prepare request payload
        $request = [
        'resource' => $json_payload['resource'],
        'method'   => $method,
        ];

        session()->put('script_call', 'true');
        $output = $service->resource($request, $service_name, $resource, $internal_access = true);
        session()->forget('script_call');

        return json_decode(json_encode(messenger::message(), true), true);
    }

     /*
     * script execution sandbox
     *
     * @param string $resource name of resource belonging to a service
     * @param array $payload request parameters
     * @return array
     */
    public function run_script($resource, $payload)
    {
        $service = new Service();

        //checking right access control right
        $access_type = $payload['resource_access_right'];
        $access_state = $service->check_resource_access_right_type($access_type['script']);
        $user_cred = Helper::get_authenticated_user_cred($access_state);

        //available internal params
        $EVENT = [
            'method'     => $payload['method'],
            'params'     => $payload['params'],
            'script'     => $payload['script'],
            'user_id'    => $user_cred['id'],
            'user_token' => $user_cred['token'],
        ];
        $script_class = new self();

//NB: position matters here
        $code = <<<EOT
  if(!function_exists('DvService')){
 \$GLOBALS['script_class'] = \$script_class;
function DvService(\$json_payload, \$service_name, \$resource, \$method){
  return call_user_func_array(array(\$GLOBALS['script_class'], 'internal_services'),array(\$json_payload,
    \$service_name, \$resource, \$method));
 }
}

$payload[script];
EOT;

        $result = eval($code);

        return $result;
    }
}
