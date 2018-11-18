<?php

namespace App\Http\Controllers;

use App\Helpers\DevlessHelper;
use App\Helpers\Helper;
use App\Helpers\Response;
use JsonRPC\Server;

class RpcController extends Controller
{
    /**
     * Relay rpc request to ActionClass.
     *
     * @param $payload
     *
     * @return \Illuminate\Http\Response
     */
    public function index($payload)
    {
        $raw_request = json_decode(file_get_contents('php://input'), true);
        $raw_request['params'] = $payload['params'];
        $edited_request = $raw_request;

        $service = $payload['service_name'];
        $method = Helper::query_string()['action'][0];
        // the service name devless is a reserved name

         $serviceMethodPath = (strtolower($service) == config('devless')['name']) ?
                            config('devless')['system_class'] :
                            config('devless')['views_directory'].$service.'/ActionClass.php';
        if (file_exists($serviceMethodPath)) {
            include_once $serviceMethodPath;
        } else {
            return Helper::interrupt(604, "The Service $service does not exist or you just misspelt it. Also be sure the service is set to active");
        }
        $server = new Server(json_encode($edited_request, true));
        $class = new \ReflectionClass($service);
        DevlessHelper::rpcMethodAccessibility($class, $method);
        $server->getProcedureHandler()->withClassAndMethod($service, $service, $method);
        return  Response::respond(637, '', json_decode($server->execute()));
        $output = json_decode($server->execute());
        unset($output->jsonrpc);
        if (isset($output->error)) {
            $status_code = 1001;
            $message = $output->error->message;
            $payload = null;
        } elseif (isset($output->result) && (gettype($output->result) == 'array') || gettype($output->result) == 'object') {
            $status_code = $output->result->status_code;
            $message = $output->result->message;
            $payload = $output->result->payload;
        } else {
            $status_code = 1001;
            $message = 'Invalid credentials provided';
            $payload = null;
        }

        return Response::respond($status_code, $message, $payload);
    }
}
