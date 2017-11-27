<?php

namespace Devless\Script;

use App\Helpers\Helper;
use Devless\RulesEngine\Rules;
use App\Helpers\DevlessHelper;
use App\Helpers\Assert as Assert;
use App\Http\Controllers\ServiceController as Service;

class ScriptHandler
{
    use compiler;

    /**
     * script execution sandbox.
     *
     * @param $Dvresource
     * @param array   $payload request parameters
     *
     * @return array
     *
     * @internal param string $resource name of resource belonging to a service
     */
    public function run_script($Dvresource, $payload)
    {
        $service = new Service();
        $rules = new Rules();
        $rules->requestType($payload);
        $user_cred = Helper::get_authenticated_user_cred(false);
        $user_cred = (empty($user_cred)) ? ['id' => '', 'token' => ''] : $user_cred;
        $accessed_table = DevlessHelper::get_tablename_from_payload($payload);
        //available internal params

        $EVENT = [
            'method' => $payload['method'],
            'params' => [],
            'script' => $payload['script'],
            'user_id' => $user_cred['id'],
            'user_token' => $user_cred['token'],
            'request_type' => $Dvresource,
            'request_phase' =>$payload['request_phase'],
            'access_rights' => $payload['resource_access_right'],
            'requestPayload' => (isset($payload['ex_params']))?$payload['ex_params']:null,
            'status_code'   => (isset($payload['response_status_code']))?$payload['response_status_code']:null,
            'message'      => (isset($payload['response_message']))?$payload['response_message']:null,
            'results_payload' => (isset($payload['response_payload']))?$payload['response_payload']:null,
        ];
        
        if ($Dvresource == 'rpc') {
            $EVENT['params'] = $payload['params'];
        } elseif (isset($payload['params'][0]['field'])) {
            $EVENT['params'] = $payload['params'][0]['field'][0];
        } elseif (isset($payload['params'][0]['params'][0]['data'][0])) {
            $EVENT['params'] = $payload['params'][0]['params'][0]['data'][0];
        } elseif (isset($payload['params'][0])) {
            $EVENT['params'] = $payload['params'][0];
        }

        $devlessHelper = new DevlessHelper();
        $actual_action = $EVENT['method'];

        //NB: position matters here
        $code = <<<EOT
$payload[script];   
EOT;
        $_____service_name = $payload['service_name'];
        $_____init_vars = $payload['script_init_vars'];
        
        $exec = function () use ($code, $rules, &$EVENT, $_____service_name, $_____init_vars, $payload) {

            //store script params temporally
            $_____midRules = $rules;
            $_____midEvent = $EVENT;

            //get declared vars
            $declarationString = $_____init_vars;
            eval($declarationString);
            //restore script params
            $rules = $_____midRules;
            $EVENT = $_____midEvent;

            extract($EVENT['params'], EXTR_PREFIX_ALL, 'input');
            $rules->accessRights = $EVENT['access_rights'];
            $rules->EVENT = $EVENT;
            
            $rules->request_phase = ($EVENT['request_phase'] == 'after')?'after':'before';
            if ($rules->request_phase == 'after') {
                $rules->status_code = $EVENT['status_code'];
                $rules->message = $EVENT['message'];
                $rules->payload = $EVENT['results_payload'];
            }
            
            
            
            $imports = "use App\Helpers\Assert as assertIts;use App\Helpers\Assert as  assertIt;";
            $headers = $imports.'$rules';
            $footer  = '';
            $finalCode = (strpos($code, 'use App\Helpers\Assert')!==false)? $code : $headers.$code.$footer;
        
            if ($EVENT['request_phase'] == 'after' && isset($payload['ex_params'])) {
                extract($payload['ex_params'], EXTR_PREFIX_ALL, 'input');
            }
        
            eval($finalCode);

            $EVENT['access_rights'] = $rules->accessRights;
            $EVENT['status_code']  = $rules->status_code;
            $EVENT['message']  =  $rules->message;
            $EVENT['results_payload']  =  $rules->payload;
            $EVENT['user_id'] = $rules->EVENT['user_id'];
            ;
            

            foreach ($EVENT['params'] as $key => $value) {
                $EVENT['params'][$key] = ${'input_'.$key};
            }

            $EVENT['ex_params'] = [];
            foreach (get_defined_vars() as $key => $value) {
                if (strpos($key, 'input_') === 0) {
                    if (isset($EVENT['params'][$key])) {
                        $EVENT['params'][$key] = ${'input_'.$key};
                    } else {
                        $var_split = explode('_', $key);
                        unset($var_split[0]);
                        $key = implode($var_split, "_");
                        $EVENT['ex_params'][$key] = $value;
                    }
                }
            }
            return $EVENT;
        };

        $params = $exec();
        if ($Dvresource == 'rpc') {
            $payload['params'] = $params['params'];
            $payload['ex_params'] = $params['ex_params'];
        } elseif (isset($payload['params'][0]['field'])) {
            $payload['params'][0]['field'][0] = $params['params'];
            $payload['ex_params'] = $params['ex_params'];
        }
    
         (strpos(error_get_last()['file'], 'ScriptHandler.php') !=false)?dd():'';

        if ($EVENT['request_phase'] == 'after') {
            $results['status_code'] = $EVENT['status_code'];
            $results['message'] = $EVENT['message'];
            $results['payload'] = $EVENT['results_payload'];
        } else {
            $payload['resource_access_right'] = $EVENT['access_rights'];
            $results['payload'] = $payload;
            $results['resource'] = $Dvresource;
            
            if ($rules->request_phase == 'endNow') {
                $results['resource'] = 'endNow';
                $results['payload']['status_code'] = $EVENT['status_code'];
                $results['payload']['message'] = $EVENT['message'];
                $results['payload']['results'] = $EVENT['results_payload'];
            }
        }
        return $results;
    }
}
