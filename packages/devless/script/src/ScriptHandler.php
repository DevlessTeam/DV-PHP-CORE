<?php

namespace Devless\Script;

use App\Helpers\Helper;
use Devless\RulesEngine\Rules;
use App\Helpers\DevlessHelper;
use App\Helpers\Assert as Assert;
use App\Http\Controllers\ServiceController as Service;

class ScriptHandler
{

    public function compile_script($code)
    {
        $declarationString = '';
        $tokens = token_get_all('<?php '.$code);
        foreach ($tokens as $token) {
            if (is_array($token)) {
                $start = 1;
                if ($token[0] == 312) {
                    $variable = substr($token[1], $start);
                    $declarationString .= "$$variable = null;";
                }
            }
        }
        $compiled_script['var_init'] = $declarationString;
        $compiled_script['script'] = $code;
        return $compiled_script;
    }

    /**
     * script execution sandbox.
     *
     * @param       $Dvresource
     * @param array $payload    request parameters
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
            'status_code'   => (isset($payload['response_status_code']))?$payload['response_status_code']:null,
            'message'      => (isset($payload['response_message']))?$payload['response_message']:null,
            'results_payload' => (isset($payload['response_payload']))?$payload['response_payload']:null,
        ];
        
        if (isset($payload['params'][0]['field'])) {
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
            $_____mindEvent = $EVENT;

            //get declared vars
            $declarationString = $_____init_vars;
            eval($declarationString);
            //restore script params
            $rules = $_____midRules;
            $EVENT = $_____mindEvent;

            extract($EVENT['params'], EXTR_PREFIX_ALL, 'input');
            $rules->accessRights = $EVENT['access_rights'];
            $rules->EVENT['user_id'] = $EVENT['user_id'];
            $rules->request_phase = ($EVENT['request_phase'] == 'after')?'after':'before';
            if($rules->request_phase == 'after') {
                $rules->status_code = $EVENT['status_code'];
                $rules->message = $EVENT['message'];
                $rules->payload = $EVENT['results_payload'];    
            }
            
            
            
            $imports = "use App\Helpers\Assert as AssertIts;";
            $headers = $imports.' $rules';
            $footer  = '';
            $finalCode = (strpos($code, 'use App\Helpers\Assert')!==false)? $code : $headers.$code.$footer;
          
            eval($finalCode);

            $EVENT['access_rights'] = $rules->accessRights;
            $EVENT['status_code']  = $rules->status_code;
            $EVENT['message']  =  $rules->message;
            $EVENT['results_payload']  =  $rules->payload;
            $EVENT['user_id'] = $rules->EVENT['user_id'];;
            

            foreach ($EVENT['params'] as $key => $value) {
                $EVENT['params'][$key] = ${'input_'.$key};
            }

            return $EVENT['params'];
        };

        $params = $exec();
        if (isset($payload['params'][0]['field'])) {
            $payload['params'][0]['field'][0] = $params;
        }
        
        (error_get_last())?dd():'';

        if($EVENT['request_phase'] == 'after') {
            $results['status_code'] = $EVENT['status_code'];
            $results['message'] = $EVENT['message'];
            $results['payload'] = $EVENT['results_payload'];
        } else {
            $payload['resource_access_right'] = $EVENT['access_rights'];
            $results['payload'] = $payload;
            $results['resource'] = $Dvresource;
            
            if($rules->request_phase == 'endNow') {
                $results['resource'] = 'endNow';
                $results['payload']['status_code'] = $EVENT['status_code'];
                $results['payload']['message'] = $EVENT['message'];
                $results['payload']['results'] = $EVENT['results_payload'];
                
            }
    
        }
        
        return $results;
    }
}
