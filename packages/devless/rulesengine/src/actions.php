<?php

namespace Devless\RulesEngine;

use App\Helpers\Helper;
use App\Helpers\ActionClass;

trait actions
{
    /**
     * Checks if the  db action to be conducted around one of the tables. Eg beforeQuerying()->onTable('register', 'subscription')->succeedWith("yes"). In the example above yes will be outputted incase data is being queried from either regiter or subscription table.
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
        $this->execOrNot = (in_array($this->tableName, $expectedTableNames));
        $this->onCurrentTable = $this->execOrNot;
        $this->onTableCalled = true;
        return $this;
    }

    public function getCurrentTable()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->results = $this->tableName;
        return $this;
    }

    /**
         * Stop execution with an exception and output the message provided. Eg. afterQuering()->succeedWith("I will show up after quering")
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
         *  Stop execution with an exception and output the message provided. Eg. afterQuering()->failWith("I will show up after quering"). Difference between this and succeedWith is the status code
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
         * DevLess provides developers with ready to use code and one of the ways to access this is via the run statement. After installing an external service you may call it within the rules portion of your app using run eg: beforeCreating()->run('businessMath','discount',[10, $input_price])->getResults($input_price)
         *
         * @param $service
         * @param $method
         * @param array   $params
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
        $this->cleanOutput();
        return $this;
    }

    /**
         * In the event where you need to make say an api call the `makeExternalRequest` method becomes handy. eg beforeUpdating()->makeExternalRequest('GET', 'https://www.calcatraz.com/calculator/api?c=3%2A3')->storeAs($ans)
         * ->succeedWith($ans)
         *
         * @param STRING $method
         * @param STRING $url
         * @param JSON   $data    (opt)
         * @param JSON   $headers (optional)
         *
         * @return $this
         */
    public function makeExternalRequest($method, $url, $data = '{}', $headers = [])
    {

        if (!$this->execOrNot) {
            return $this;
        }
        
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => strtoupper($method),
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => $headers,
                )
        );

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->results = $err;
        } else {
            $this->results =  json_decode($response, true);
        }
        $this->cleanOutput();
        return $this;
    }
    /**
         * one of the ways to store results from a method with output is by using the `getResults` method. This will allow you to the output of a method
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
         * Get results from the last executed method and assign to a variable. eg: beforeQuerying()->sumUp(1.2,3,4,5)->storeAs($ans)->stopAndOutput(1001, 'summed up numbers successfully' $ans)
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
     * Assigns one variable to another just like `$sum2 = $sum` . This method works together with `to` method to achive this. eg: afterQuering()->sumUp(1,2,3,4,5)->storeAs($sum)->assign($sum)->to($sum2)->succeedWith($sum2)
     *
     * @param $input
     * @return $this
     * @internal param $input_var
     *
     */
    public function assign($input)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->results = $input;
        $this->cleanOutput();
        return $this;
    }

    /**
         * Set the name of service from which you want to use method from.
         * eg: usingService('devless')->callMethod('hello')->  withParams()->getResults($output)->succeedWith($output)
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
         * Set the name of the method after setting the service from which you will like to run.  eg:usingService('devless')->callMethod('hello')->  withParams()->getResults($output)->succeedWith($output)
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
         * eg: usingService('devless')->callMethod('getUserProfile')->  withParams(1)->getResults($output)->succeedWith($output)
         * @param can have n number of parameters
         * @return $this
         */
    public function withParams($withoutParams = false)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->checkRunConstructs($this->selectedService, $this->selectedMethod);
        (!$withoutParams && empty(func_get_args()))?$this->stopAndOutput(1111, 'Rule Error', ['suggestion'=>'You will have to provide parameters eg: ->withParams("a","b")']):'';

        $this->run($this->selectedService, $this->selectedMethod, func_get_args());
        return $this;
    }

    /**
         * Use this in place of `params()` incase the service method you want to run has no parameters
         * eg: usingService('devless')->callMethod('hello')->withoutParams()->getResults($output)->succeedWith($output)
         * @return $this
         */
    public function withoutParams()
    {
        $this->withParams(true);
        return $this;
    }
    public function checkRunConstructs($service, $method)
    {

        (!$service || !$method)?$this->stopAndOutput(1111, 'Rule Error', ['suggestion'=>'To call on a method within a Service try `usingService("serviceName")->callMethod("methodName")->withParams("a", "b")']):'';
        return $this;
    }

    /**
         * `to` keyword should be used together with `assign` to assign either variables or values to a new variable
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
        $this->cleanOutput();
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
         * Behaves just like the `assign` method but has a much shorter construct. where you will assign say the string "edmond" to variable $name using `assign`  `assign("edmond")->to($name)` , `assignValue("edmond", $name)` provides a much shorter construct but loses its redability.
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
         * Should you perform some rules and based on that will like to exit earlier with a response before the actual db command completes you will want to use `stopAndOutput` eg: beforeQuerying()->usingService('devless')->callMethod('getUserProfile')->withParams(1)->storeAs($profile)->stopAndOutput(1000, 'got profile successfully', $profile).
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
         * List out all methods as well as get docs on specific method eg: ->help('stopAndOutput')
         * @param $methodToGetDocsFor
         * @return $this
         */
    public function help($methodToGetDocsFor = null)
    {

        $methods = get_class_methods($this);

        $exemptedMethods = ['__construct','requestType','__call','useArgsOrPrevOutput','read','commonMutationTask', 'set', 'from'];
        $methodList = [];
        $rules = new Rules();
        $getMethodDocs = function ($methodName) use ($exemptedMethods, $rules) {
            if (!in_array($methodName, $exemptedMethods)) {
                $method = new \ReflectionMethod($rules, $methodName);
                $methodDocs = str_replace("*/", "", $method->getDocComment());
                $methodDocs = str_replace("/**", "", $methodDocs);
                return $methodDocs = str_replace("* *", "||", $methodDocs);
            } else {
                return false;
            }
        };
        if ($methodToGetDocsFor) {
            $docs = $getMethodDocs($methodToGetDocsFor);
            if ($docs) {
                $methodList[$methodToGetDocsFor] = $docs;
            }
        } else {
            $count = 0;
            foreach ($methods as $methodName) {
                $methodDocs = $getMethodDocs($methodName);
                if ($methodDocs) {
                    $methodList["*".$count."*"] = "**********************************************************************************************************";

                    $methodList[$methodName] = $methodDocs;
                }
                $count++;
            }
        }


        $this->stopAndOutput(1000, 'Help on methods', $methodList);
        return $this;
    }
    /**
         * Evaluate PHP expressions eg:beforeQuering()->evaluate("\DB::table('users')->get()")->storeAs($output)->stopAndOutput(1001, 'got users successfully', $output)
         * @param $expression
         * @return $this
         */
    public function evaluate($expression, $variables = [])
    {
        if (!$this->execOrNot) {
            return $this;
        }
        extract($variables);
        $code = <<<EOT
        $expression
EOT;
        $this->results = eval('return '.$code.';');
        $this->cleanOutput();
        return $this;
    }

    public function using($data)
    {
        if (!$this->execOrNot) {
            return $this;
        }

        $this->sharedStore = $data;
        return $this;
    }


    /**
         * @param $input
         *
         * @return $this
         */
    public function set($input)
    {
        $this->assign($input);
        return $this;
    }

    public function read()
    {
        return $this;
    }
}
