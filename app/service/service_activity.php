<?php

namespace App\service;

use App\Service;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Service as serviceModel;
use Devless\Schema\DbHandler as Db;
use Devless\Script\ScriptHandler as script;

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
    public function get_params($method, $request)
    {
        if (in_array($method, ['POST', 'DELETE', 'PATCH'])) {
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
             'delete' => 1, 'script' => 1, 'schema' => 1, 'script' => 1, 'create' => 1, ];

        $resource_access_right = $service->resource_access_right;
        $resource_access_right = json_decode($resource_access_right, true);
        $resource_access_right = ($master_access) ?
            $master_resource_rights : $resource_access_right;

        return $resource_access_right;
    }

    /**
     * check user resource  action access right eg: query db or write to table.
     *
     * @param   $access_type
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
     * @params array $payload
     *
     * @return array
     */
    public function before_assigning_service_action($resource, $payload, $internalAccess = false)
    {
        $output['resource'] = $resource;
        $output['payload'] = $payload;
        if ($resource != 'schema' && !$internalAccess) {
            $script = new script();
            $output = $script->run_script($resource, $payload);
        }

        return $output;
    }

    /**
     * Initialize variables in Rules.
     *
     * @param $code
     *
     * @return string
     */
    public function var_init($code)
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

        return $declarationString;
    }
}
