<?php

use Devless\RulesEngine\Rules;
use Devless\Script\ScriptHandler;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class mutateResponseTest extends TestCase
{

    public $rules = null;
    public $resource_type = 'db';
    public $ScriptHandler = null;

    public function __construct()
    {

        $this->rules = new Rules();
        $this->ScriptHandler = new ScriptHandler();
    }
   
    public function testMutateStatusCode()
    {
        $params = ["table"=>["products"]];
        $script = '->afterQuerying()->mutateStatusCode(343)';
        $output = $this->scriptRunner('service_name', $script, 'GET', $params);
        $this->assertEquals(343, $output["status_code"]);
    }

  
    public function testMutateResponseMessage()
    {
        $params = ["table"=>["products"]];
        $script = '->afterQuerying()->mutateResponseMessage("sample message")';
        $output = $this->scriptRunner('service_name', $script, 'GET', $params);
        $this->assertEquals('sample message', $output['message']);
    }
  
    public function testMutateResponsePayload()
    {
        $params = ["table"=>["products"]];
        $script = '->afterQuerying()->mutateResponsePayload(["name"=>"edmond"])';
        $output = $this->scriptRunner('service_name', $script, 'GET', $params);
        $this->assertEquals(["name"=>"edmond"], $output["payload"]);
    }
    private function scriptRunner($service, $script, $method, $params)
    {
        $rulesInstance = $this->rules;
        $scriptEngine = $this->ScriptHandler;
       
        $compiled = $scriptEngine->compile_script($script);
        $samplePayload =  $this->getSamplePayload(
            'service_name',
            $compiled['var_init'],
            $compiled['script'],
            null,
            $method,
            $params,
            'after'
        );

        $scriptResults = $scriptEngine->run_script('db', $samplePayload);
       
        return $scriptResults;
    }
    private function getSamplePayload(
        $service_name,
        $script_init_vars,
        $script,
        $resource_access_right,
        $method,
        $params,
        $request_type = 'before'
    ) {
        $resource_access_right = '{"query":"0","create":"0","update":"0","delete":"0","schema":"0","script":"0","view":"0"}';
        return  [
        'id' => 1,
        'service_name' => $service_name,
        'database' => 'default',
        'driver' => null,
        'hostname' => null,
        'username' => null,
        'password' => null,
        'script_init_vars' => $script_init_vars,
        'calls' => null,
        'resource_access_right' => $resource_access_right,
        'script' => $script,
        'port' => null,
        'method' => $method,
        'params' => $params,
        'request_phase' => $request_type
        ];
    }
}
