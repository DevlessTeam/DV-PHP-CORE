<?php

namespace Devless\Script;

use Devless\RulesEngine\Rules;
use App\Helpers\Messenger as messenger;
use App\Http\Controllers\ServiceController as Service;

class ScriptHandler
{

    /**
     * script execution sandbox.
     *
     * @param    $Dvresource
     * @param    array      $payload request parameters
     * @return   array
     * @internal param string $resource name of resource belonging to a service
     */
    public function run_script($Dvresource, $payload)
    {

        $service = new Service();
        $rules = new Rules();
        $rules->requestType($payload);
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
        $exec = function () use ($code, $rules, $EVENT, $_____service_name, $_____init_vars, $payload) {

            //store script params temporally
            $_____midRules = $rules;
            $_____mindEvent = $EVENT;
            //get declared vars
            $declarationString = $_____init_vars ;
            eval($declarationString);
            //restore script params
            $rules = $_____midRules;
            $EVENT = $_____mindEvent;
            extract($EVENT['params'], EXTR_PREFIX_ALL, 'input');
            eval($code);
            foreach ($EVENT['params'] as $key => $value) {
                $EVENT['params'][$key] = ${'input_'.$key};
            }

            return $EVENT['params'];
        };

        ob_start();
        $params = $exec();
        if (isset($payload['params'][0]['field'])) {
            $payload['params'][0]['field'][0] = $params;
        }
        ob_end_clean();

        $results['payload'] = $payload;
        $results['resource'] = $Dvresource;

        return $results;
    }
}
