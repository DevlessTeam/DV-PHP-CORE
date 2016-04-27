<?php

namespace App\Http\Middleware;

require_once 'error_heap.php';

use Closure;

class Validator 
{
        
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {  
      
        # get server payload 
        $server_params = (array)$request->server;
        foreach($server_params as $server_param){
            $request_method = $server_param['REQUEST_METHOD'];
        }
        if($request_method == 'POST' or $request_method == 'PUT')
            if(isset($request['resource'])){ 
                
                #control flow 
                echo "everything went through is the only reason i am here";
            }
            else
            {    $error_code = 800;
                 $error_heap = new \ErrorHeap();
                 $this->interrupt($error_code, 
                         $error_heap->find_stack($error_code));

            }   
            return $next($request);

        }

             /**
         * stops request processing and returns error payload 
         *
         * @param  error code  $error_code
         * @param  error message  $message
         * @return json 
         */
        public function  interrupt($error_code,$message){
            $premature_response = [
                'status_code' => $error_code,
                'message' => $message,
                'payload' => []
            ];
            dd(json_encode($premature_response));
        }
        public function public_test_function(){
    //this is a public test function    
        }
    }
