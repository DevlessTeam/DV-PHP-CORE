<?php

namespace App\service;

use Validator;
use App\Service;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Service as serviceModel;
use Devless\Schema\DbHandler as Db;
use App\Helpers\DevlessHelper as DLH;
use Devless\Script\ScriptHandler as script;
use App\Http\Controllers\ViewController as DvViews;

trait service_activity
{
    /**
     *check if service exists.
     *
     * @param string $service_name name of service
     *                             return array of service values
     */
    public function service_exist($service_name)
    {
        if ($current_service = serviceModel::where('name', $service_name)->where('active', 1)->first()
        ) {
            return $current_service;
        } elseif (config('devless')['devless_service']->name == $service_name) {
            $current_service = config('devless')['devless_service'];

            return $current_service;
        } else {
            Helper::interrupt(604);
        }
    }

    /**
     * get parameters set in from request.
     *
     * @param string $method  reuquest method type
     * @param array  $request request parameters return array of parameters
     *                        return array of parameters
     *
     * @return array|mixed
     */
    public function get_params($method, $request, $resource = null)
    {
        if ($resource == 'rpc') {
            $parameters = $request['params'];
        } elseif (in_array($method, ['POST', 'DELETE', 'PATCH'])) {
            $parameters = $request['resource'];
        } elseif ($method == 'GET') {
            $parameters = Helper::query_string();
        } else {
            Helper::interrupt(
                608,
                'Request method '.$method.
                ' is not supported'
            );
        }
        return $parameters;
    }

    /**
     * get and convert resource_access_right to array.
     *
     * @param object $service       service payload
     * @param bool   $master_access
     *
     * @return array resource access right
     */
    private function _get_resource_access_right($service, $master_access = false)
    {

        $master_resource_rights = ['query' => 1, 'update' => 1,
             'delete' => 1, 'script' => 1, 'schema' => 1, 'script' => 1, 'create' => 1, 'view' => 1];

        $resource_access_right = $service->resource_access_right;
        $resource_access_right = json_decode($resource_access_right, true);
        $resource_access_right = ($master_access) ?
            $master_resource_rights : $resource_access_right;
            
        return $resource_access_right;
    }

    /**
     * check user resource  action access right eg: query db or write to table.
     *
     * @param $access_type
     *
     * @return bool
     *
     * @internal param object $service service payload
     */
    public function check_resource_access_right_type($access_type)
    {
        $is_user_login = Helper::is_admin_login();
        if (!$is_user_login && $access_type == 0) { //private
            Helper::interrupt(627);
        } elseif ($access_type == 1) {  //public
            return false;
        } elseif ($access_type == 2) { //authentication required
            return true;
        }

        return true;
    }
    /**
     * operations to execute before assigning action to resource.
     *
     * @param string $resource
     * @param array  $payload
     *
     * @return array
     */
    public function before_assigning_service_action($resource, $payload)
    {
        $payload['request_phase'] = 'before';
        $output['resource'] = $resource;
        $output['payload'] = $payload;

        $script_output = $this->script_executioner($resource, $payload);

        return ($script_output)?:$output;
    }

    /**
     * operations to execute after resource processes order.
     *
     * @param string $resource
     * @param array  $payload
     *
     * @return array
     */
    public function after_resource_process_order($resource, $requestPayload, $status_code, $message, $payload)
    {
        $requestPayload['request_phase'] = 'after';
        $requestPayload['response_status_code'] = $status_code;
        $requestPayload['response_message'] = $message;
        $requestPayload['response_payload'] = $payload;

        $script_output = $this->script_executioner($resource, $requestPayload, $internalAccess = false);
        return ($script_output)?:['status_code' => $status_code, 'message'=> $message, 'payload' => $payload];
    }

    /**
     * create new DevLess service
     *
     * @param object $request
     *
     */
    public function create_service($service_name, $description='', $database='', $username='', $password='',   $hostname='', $driver='default', $port='')
    {
        $service = new Service();
        $scriptHandler = new script();
        $service_name_from_form = $service_name;

        //service name clean up 
        $service_name_from_form = preg_replace('/\s*/', '', $service_name_from_form);
        $service_name_from_form = str_replace('-', '_', $service_name_from_form);
        $service_name = $service_name_from_form;
        
        $service_name = (strtolower($service_name) == 'devless')? strtolower($service_name) : $service_name;

        $is_keyword = $this->is_service_name_php_keyword($service_name);

      
        
        $template_type = (strtolower($service_name) == 'devless')? strtolower($service_name) : 'default';
        

        $validator = Validator::make(
            ['Service Name' => $service_name, 'Devless' => 'devless'],
            [
                'Service Name' => 'required|alpha|unique:services,name|min:3|max:15',
            ]
        );

        if ($validator->fails() || $is_keyword) {
            $errors = $validator->messages();
            $message = (!$validator->fails() )?"Sorry but service could not be created":"Sorry but $service_name is a keyword and can't be used as a `service name`";
            DLH::flash($message, 'error');

            return [false,$errors];
        }
        $service->name = $service_name;
        $serviceFields = ['description'=> $description, 'username'=>$username, 'password'=>$password,
                'database' => $database, 'password' => $password, 'hostname'=> $hostname, 'driver'=>$driver, 'port'=>$port];
        foreach ($serviceFields as  $fiieldname => $serviceField) {
            $service->{$fiieldname} = $serviceField;
            $connection[$fiieldname] = $serviceField;
        }

        $service->script_init_vars = '$rules = null;';
        $service->resource_access_right = ($template_type == 'default') ? '{"query":1,"create":1,"update":1,"delete":1,"schema":0,"script":0, "view":0}'
                        : '{"query":0,"create":0,"update":0,"delete":0,"schema":0,"script":0, "view":0}';
        $service->active = 1;
        $service->raw_script = DLH::script_template($template_type);
        $compiled_script  = $scriptHandler->compile_script(DLH::script_template($template_type));
        $service->script = $compiled_script['script'];
        $db = new Db();
        if (!$db->check_db_connection($connection)) {
            DLH::flash('Sorry connection could not be made to Database', 'error');
        } else {
            //create initial views for service
            $views = new DvViews();
            $type = 'init';
            $payload['serviceName'] = $service_name;
            $views_created = $views->create_views($service_name, $type);
            ($service->save() && $views_created)
                ?
                DLH::flash('Service created successfully', 'success') :
                DLH::flash('Service could not be created', 'error');
            ($service_name == 'devless')?$this->create_devless_profile_table(): false;
        }

        return [true,$service];

    }

    public function create_service_from_request($request,$natural_request=true)
    {

        $params  = [$request->input('name'), $request->input('description'),  $request->input('database'), $request->input('username'), $request->input('password'), $request->input('hostname'),$request->input('driver'), $request->input('port')];
        $this->create_service(...$params);
    }

    /**
     * script execution engine
     *
     * @param  string $resource
     * @param  array  $payload
     * @param  bol    $internalAccess
     * @return array
     */
    public function script_executioner($resource, $payload)
    {
        $output = false;
        if ($resource != 'schema') {
            $output = ( new script() )->run_script($resource, $payload);
    }
        return $output;
    }

    public function create_devless_profile_table() 
    {
        $schema = config()['devless']['devless_profile_schema'];

       return  $this->assign_to_service('devless','schema','POST',$schema, true);
    }

    /**
    * list of PHP keywords
    * @param string $word 
    * @return bol 
    */
    public function is_service_name_php_keyword($word)
    {

        $keywords = ['__halt_compiler', 'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final', 'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'namespace', 'new', 'or', 'print', 'private', 'protected', 'public', 'require', 'require_once', 'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor','__CLASS__', '__DIR__', '__FILE__', '__FUNCTION__', '__LINE__', '__METHOD__', '__NAMESPACE__', '__TRAIT__'];

        return in_array(strtolower($word), $keywords);
    }
}
