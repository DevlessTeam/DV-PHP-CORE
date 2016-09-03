<?php

namespace App\Http\Controllers;


use JsonRPC\Server;
use App\Helpers\Helper;
use App\Helpers\Response;

class RpcController extends Controller
{
    /**
     * Relay rpc request to ActionClass
     *
     * @param $payload
     * @return \Illuminate\Http\Response
     */
    public function index($payload)
    {

        $service = $payload['service_name'];
        $method  = Helper::query_string()['action'][0];

        $serviceMethodPath = config('devless')['views_directory'].$service.'/ActionClass.php';

        (file_exists($serviceMethodPath))?
            require_once $serviceMethodPath : false;

        $server = new Server();
        $server->getProcedureHandler()->withClassAndMethod($service, $service, $method);

        return  Response::respond(637, null, json_decode($server->execute()));
    }


}
