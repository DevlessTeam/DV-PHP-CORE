<?php

    namespace Devless\RulesEngine;

    use App\Helpers\Helper;
    use App\Helpers\ActionClass;

    trait actions
    {
        /**
         * check if on intended table.
         *
         * @param string $expectedTableName
         *
         * @return mixed|string
         */
        public function onTable()
        {
            $expectedTableNames = func_get_args();
            if (!$this->execOrNot) {
                return $this;
            }
           
            $this->tableName = (is_array($this->tableName)) ? $this->tableName[0] : $this->tableName;
            $this->execOrNot = (in_array($this->tableName , $expectedTableNames));

            return $this;
        }

        /**
         * Stop execcution with an exception.
         *
         * @param null $msg
         *
         * @return mixed|string
         */
        public function succeedWith($msg = null)
        {
            if (!$this->execOrNot) {
                return $this;
            }

            $msg  = (is_array($msg))? json_encode($msg):$msg;
            Helper::interrupt(1000, $msg);
            return $this;
            
        }

        /**
         * Stop execution with an exception.
         *
         * @param null $msg
         *
         * @return mixed|string
         */
        public function failWith($msg = null)
        {
            if (!$this->execOrNot) {
                return $this;
            }
            $msg  = (is_array($msg))? json_encode($msg):$msg;
            Helper::interrupt(1001, $msg);
            return $this;
        }
        /**
         * Call on an ActionClass .
         *
         * @param  $service
         * @param  $method
         * @param null $params
         *
         * @return mixed|string
         */
        public function run($service, $method, $params = null, $remoteUrl = null, $token = null)
        {
            if (!$this->execOrNot) {
                return $this;
            }
            
            $params = ($params) ? $params : [];

            if ($remoteUrl && $token) {
                $this->results = ActionClass::remoteExecute($service, $method, $params, $remoteUrl, $token);
            } else {
                $this->results = ActionClass::execute($service, $method, $params);
            }
            
            return $this;

        }

        /**
         * Make remote requests
         *
         * @param  STRING $method
         * @param  STRING $url
         * @param  JSON $data
         *
         * @return $this
         */
        public function makeExternalRequest($method, $url, $data='{}', $headers=[])
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => strtoupper($method),
              CURLOPT_POSTFIELDS => $data,
              CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
              $this->results = $err;
            } else {
                $this->results =  json_decode($response, true);

            }
            return $this;
        }
        /**
         * Get results variable and set to variable.
         *
         * @param $input_var
         *
         * @return $this
         */
        public function getResults(&$input_var)
        {
            if (!$this->execOrNot) {
                return $this;
            }
            $this->to($input_var);
            return $this;
        }

        /**
         * Get results variable, and set to variable.
         *
         * @param $input_var
         *
         * @return $this
         */
        public function storeAs(&$input_var)
        {
            $this->getResults($input_var);
            return $this;
        }

        /**
         * Assign one input to another 
         *
         * @param $input_var
         *
         * @return $this
         */
        public function assign($input)
        {
            if (!$this->execOrNot) {
                return $this;
            }
            $this->results = $input;    
            return $this;
        }

        /**
         * This is similar to assign(), set() is used  together with to() to
         * set variables to values eg set($name)->to("edmond").
         *
         * @param $input
         *
         * @return $this
         */
        public function set($input)
        {
            $this->assign($input);     
            return $this;       
        }

        /**
         * Set the name of service from which you want to use method from.
         * Complete eg: usingService('devless')->callMethod('hello')->  withParams()->getResults($output)->succeedWith($output)
         * @param $serviceName
         * @return $this
         */
        public function usingService($serviceName)
        {
            if (!$this->execOrNot) {
                return $this;
            }
            $this->selectedService = $serviceName;
            return $this;
        }

        /**
         * set the name of the method after setting the service from which you will like to run. complete eg:usingService('devless')->callMethod('hello')->  withParams()->getResults($output)->succeedWith($output)
         * @param $methodName
         * @return $this
         */
        public function callMethod($methodName)
        {
            if (!$this->execOrNot) {
                return $this;
            }
            $this->selectedMethod = $methodName;
            $this->checkRunConstructs($this->selectedService, $this->selectedMethod);
            return $this;
        }

        /**
         * Set parameters for method from which you will like to run
         * complete eg: usingService('devless')->callMethod('hello')->  withParams()->getResults($output)->succeedWith($output)
         * @param as many as possible
         * @return $this
         */
        public function withParams($internalCall=false)
        {
            if (!$this->execOrNot) {
                return $this;
            }
            $this->checkRunConstructs($this->selectedService, $this->selectedMethod);
            (!$internalCall && empty(func_get_args()))?$this->stopAndOutput(1111,'Rule Error',['suggestion'=>'You will have to provide parameters eg: ->withParams("a","b")']):'';

            $this->run($this->selectedService, $this->selectedMethod, func_get_args());
            return $this;
        }

        public function withoutParams()
        {
            $this->withParams(true);
            return $this;   
        }
        public function checkRunConstructs($service, $method)
        {
            
            (!$service || !$method)?$this->stopAndOutput(1111, 'Rule Error',['suggestion'=>'To call on a method within a Service try `usingService("serviceName")->callMethod("methodName")->withParams("a", "b")']):'';
            return $this;
        }

        /**
         * Get results variable and set to variable.
         *
         * @param $output
         *
         * @return $this
         */
        public function to(&$output)
        {
            if (!$this->execOrNot) {
                return $this;
            }
            $output = $this->results;

            return $this;
        }

        /**
        * Get results variable and set to variable.
         *
         * @param $output
         *
         * @return $this
         */
        public function from($output)
        {
            if (!$this->execOrNot) {
                return $this;
            }

            $this->assign($output);
            return $this;
        }

        /**
         * Assign $input to $output 
         *
         * @param $input
         * @param $output  
         *
         * @return $this
         */
        public function assignValues(&$input, &$output)
        {
            if (!$this->execOrNot) {
                return $this;
            }

            $output = $input;    
            return $this;
        }

        public function find($value)
        {
            if (!$this->execOrNot) {
                return $this;
            }

            if($value != null){
                $this->results = $value;
            }
            return $this;
        }

        /**
         * Stop execution and output results
         *
         * @param $status_code
         * @param $message 
         * @param $payload 
         *
         * @return $this
         */
        public function stopAndOutput($status_code, $message, $payload)
        {
            if (!$this->execOrNot) {
                return $this;
            }
            
            $this->request_phase = 'endNow';
            $this->status_code = $status_code;
            $this->message = $message;
            $this->payload = $payload;
            $this->execOrNot = false;
            $this->stopAndOutputCalled = true;
            return $this;
        }

        /**
         * List out all possible callbale methods as well as get docs on specific method eg: ->help('stopAndOutput')
         * @param $methodToGetDocsFor
         * @return $this;
         */
        public function help($methodToGetDocsFor=null)
        {
            
            $methods = get_class_methods($this);
            
            $exemptedMethods = ['__construct','requestType','__call','useArgsOrPrevOutput','read','commonMutationTask'];
            $methodList = [];
            $rules = new Rules();
            $getMethodDocs = function($methodName) use($exemptedMethods, $rules) {
                if(!in_array($methodName, $exemptedMethods)){
                    $method = new \ReflectionMethod($rules, $methodName); 
                    $methodDocs = str_replace("*/","",$method->getDocComment());
                    $methodDocs = str_replace("/**","",$methodDocs);
                    return $methodDocs = str_replace("* *","||",$methodDocs);
                } else { return false;}
            };
            if($methodToGetDocsFor) {
                $docs = $getMethodDocs($methodToGetDocsFor);
                if($docs) {
                     $methodList[$methodToGetDocsFor] = $docs;
                }
            } else {
                foreach ($methods as $methodName) {
                  $methodDocs = $getMethodDocs($methodName);
                  if($methodDocs) {
                    $methodList[$methodName] = $methodDocs;  
                  }
                }
            }
            
            
            $this->stopAndOutput(1000, 'List of callable methods', $methodList);
            return $this;

        }

        public function read()
        {
            return $this;
        }

    }