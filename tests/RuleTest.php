<?php

use Devless\RulesEngine\Rules;
use Devless\Script\ScriptHandler;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RuleTest extends TestCase
{

    public $rules = null;
    public $resource_type = 'db';
    public $ScriptHandler = null;

    public function __construct()
    {

        $this->rules = new Rules();
        $this->ScriptHandler = new ScriptHandler();
    }
    /**
     * Test for.
     *
     * @return void
     */
    public function testStopAndOutput()
    {
        $rulesInstance = $this->rules;

        $output = $rulesInstance->stopAndOutput(419, 'sample message', []);

        $this->assertEquals('endNow', $output->request_phase);
        $this->assertEquals(419, $output->status_code);
        $this->assertEquals('sample message', $output->message);
    }
   
    public function testOnTable()
    {
        $params = ["table"=>["products"]];
        $script = '->beforeQuerying()->onTable("products")->stopAndOutput(419, "sample message", [])';
        $output =  $this->scriptRunner('service_name', $script, 'GET', $params);
    
        $this->assertEquals($output['payload']['request_phase'], 'before');
        $this->assertEquals($output['payload']['status_code'], 419);
        $this->assertEquals($output['payload']['message'], 'sample message');
        $this->assertEquals($output['resource'], 'endNow');
    }
  
    public function testSucceedWith()
    {
        $rulesInstance = $this->rules;
        try {
            $rulesInstance->succeedWith("message");
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testFailWith()
    {
        $rulesInstance = $this->rules;
        try {
            $rulesInsstance->failWith("message");
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }
    
    public function testRun()
    {
        $params = ["table"=>["products"]];
        $script = '->beforeQuerying()->run("devless", "hello", [])->storeAs($ans)->stopAndOutput(419, "$ans", [])';
        $output = $this->scriptRunner('service_name', $script, 'GET', $params);
    
        $this->assertEquals("Hello World!", $output["payload"]["message"]);
    }

    public function testAssign()
    {
        $params = ["table"=>["products"]];
        $script = '->beforeQuerying()->run("devless", "hello", [])->storeAs($ans)->assign($ans)->to($secondAns)->stopAndOutput(419, $secondAns, [])';
        $output = $this->scriptRunner('service_name', $script, 'GET', $params);
    
        $this->assertEquals("Hello World!", $output["payload"]["message"]);
    }

    public function testHelp()
    {
        $params = ["table"=>["products"]];
        $script = '->beforeQuerying()->help()';
        $output = $this->scriptRunner('service_name', $script, 'GET', $params);
    
        $this->assertEquals("Help on methods", $output["payload"]["message"]);
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
            $params
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
        $params
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
        'request_phase' => 'before'
        ];
    }
}
