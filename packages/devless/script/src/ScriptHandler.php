<?php

namespace Devless\Script;

use Devless\RulesEngine\Rules;
use App\Helpers\DevlessHelper;
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
        $accessed_table = DevlessHelper::get_tablename_from_payload($payload);
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

        $devlessHelper = new DevlessHelper();
        $actual_action = $EVENT['method'];

        $applyCustomAuth = function($service_name, $auth_table, $affected_tables, $expected_action) use($devlessHelper, $actual_action, $accessed_table) {
                $auth_actions = [
                    'onQuery'  => 'GET',
                    'onUpdate' => 'PATCH',
                    'onDelete' => 'DELETE',
                    'onCreate' => 'POST',
                    'all'      => 'all'
                ];
                if($auth_actions[$expected_action] !== $actual_action && $auth_actions[$expected_action] != "all"){return;}

                $devlessHelper->serviceAuth($service_name, $auth_table, $affected_tables, $accessed_table);
        };

        //NB: position matters here
        $code = <<<EOT
$payload[script];
EOT;
        $_____service_name = $payload['service_name'];
        $_____init_vars = $payload['script_init_vars'];
        $exec = function () use ($code, $rules, $EVENT, $_____service_name, $_____init_vars, $payload, $applyCustomAuth) {

            //store script params temporally
            $_____midRules = $rules;
            $_____mindEvent = $EVENT;
            $_____applyCustomAuth = $applyCustomAuth;
            //get declared vars
            $declarationString = $_____init_vars ;
            eval($declarationString);
            //restore script params
            $rules = $_____midRules;
            $EVENT = $_____mindEvent;
            $applyCustomAuth = $_____applyCustomAuth;
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
