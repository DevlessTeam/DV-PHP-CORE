<?php

namespace App\service;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Devless\Schema\DbHandler as Db;
use App\Helpers\Response as Response;
use Devless\Script\ScriptHandler as script;
use App\Http\Controllers\RpcController as Rpc;

trait service_assignment
{
    /**
     * assign request to a devless resource eg: db, view, script, schema, .
     *
     * @param $service_name
     * @param string       $resource
     * @param array        $method          http verb
     * @param null         $parameters
     * @param bool         $internal_access
     *
     * @return Response
     *
     * @internal param string $service name of service to be access
     * @internal param array $parameter contains all parameters passed from route
     * @internal param bool $internal_service true if service is being called internally
     */
    public function assign_to_service(
        $service_name,
        $resource,
        $method,
        $parameters = null,
        $internal_access = false
    ) {
        $current_service = $this->service_exist($service_name);
        if (!$current_service == false) {
            //check service access right
            $is_it_public = $current_service->public;
            $is_admin = Helper::is_admin_login();
            
            $accessed_internally = ($is_admin)?:$internal_access;
            if ($is_it_public == 0 || $is_admin == true) {
                $resource_access_right =
                  $this->_get_resource_access_right($current_service, $accessed_internally);
                $payload =
                    [
                        'id' => $current_service->id,
                        'service_name' => $current_service->name,
                        'database' => $current_service->database,
                        'driver' => $current_service->driver,
                        'hostname' => $current_service->hostname,
                        'username' => $current_service->username,
                        'password' => $current_service->password,
                        'script_init_vars' => $current_service->script_init_vars,
                        'calls' => $current_service->calls,
                        'resource_access_right' => $resource_access_right,
                        'script' => $current_service->script,
                        'port' => $current_service->port,
                        'method' => $method,
                        'params' => $parameters,
                    ];
                    // run script before assigning to method
                    
                if ($resource != 'view' && ($resource != 'rpc' || (isset($payload['service_name']) && strtolower($payload['service_name']) == 'devless') )) {
                    $newServiceElements = $this->before_assigning_service_action($resource, $payload);
                        
                    $resource = $newServiceElements['resource'];
                    $payload = $newServiceElements['payload'];
                }
                
                    //keep names of resources in the singular
                    /**
                * NB: all resource names from the frontend are converted to lower case meaning before case runs
                * meaning if you make a resource name uppercase within the case it will not be available outside but only useable within here . Looking @ you "endNow"
                */
                switch ($resource) {
                    case 'db':
                        $db = new Db();
                       try{
                        $response = $db->access_db($payload);
                        } catch (\Exception $e) {
                            $response['message'] = $e->getMessage(); 
                            $response['status_code'] = $e->getCode(); 
                            $response['payload'] = []; 
                        }
                        if ($resource != 'view' && $resource != 'rpc') {
                              return $this->after_resource_process_order($resource, $payload, $response['status_code'], $response['message'], $response['payload']);
                        }
                        return $response;
                      
                    break;
                    case 'schema':
                        $db = new Db();
                        return $db->create_schema($payload);
                    break;
                    case 'view':
                        return $payload;
                    case 'rpc':
                        ($method != 'POST') ? Helper::interrupt(639) : true;
                        $rpc = new Rpc();
                        try{
                            $response = $rpc->index($payload);
                        } catch (\Exception $e) {
                            $response['message'] = $e->getMessage(); 
                            $response['status_code'] = $e->getCode(); 
                            $response['payload'] = []; 
                        } catch (\Exception $e) {


                        }
                        return $this->after_resource_process_order($resource, $payload, $response['status_code'], $response['message'], $response['payload']);
                    case 'endNow':
                        return [
                        'status_code' => $payload['status_code'],
                        'message' => $payload['message'],
                        'payload' => $payload['results'],
                            ];
                    default:
                        Helper::interrupt(605);
                        break;
                }
            } else {
                Helper::interrupt(624);
            }
        }
    }
}
