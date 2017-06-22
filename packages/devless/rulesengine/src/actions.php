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
        public function getRunResult(&$input_var)
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
            $this->getRunResult($input_var);
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

        public function usingService($serviceName)
        {
            if (!$this->execOrNot) {
                return $this;
            }
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
            return $this;
        }

        public function help()
        {
            
            $output = get_class_methods($this);
            unset($output[0], $output[1], $output[2], $output[3], $output[count($output)-1]);
            $output = array_values($output);
            $count = 0;
            $rules = new Rules();
            foreach ($output as $methodName) {
                $method = new \ReflectionMethod($rules, $methodName); 
                $methodDocs = str_replace("*/","",$method->getDocComment());
                $methodDocs = str_replace("/**","",$methodDocs);
                $methodDocs = str_replace("* *","||",$methodDocs);
                $output[$count] = $methodName.' :  '.$methodDocs;
            $count++;
            }
            
            $this->stopAndOutput(1000, 'List of all callable methods', $output);
            return $this;

        }

    }