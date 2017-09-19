<?php

use Devless\RulesEngine\Rules;
use Devless\Script\ScriptHandler;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class tableActionTest extends TestCase
{

 public $rules = null;
 public $resource_type = 'db';
 public $ScriptHandler = null;

 public function __construct()
 {

   $this->rules = new Rules();
   $this->ScriptHandler = new ScriptHandler();
}	
   
   public function testbeforeQuerying()
   {
	$params = ["table"=>["products"]];
	$script = '->beforeQuerying()->stopAndOutput(419, "sample message", [])';
	$output = $this->scriptRunner('service_name', $script, 'GET', $params);
	$this->assertEquals("sample message", $output["payload"]["message"]); 
  }


   public function testAfterQuerying()
   {
	$params = ["table"=>["products"]];
	$script = '->afterQuerying()->onQuery()->stopAndOutput(419, "sample message", [])';
	$output = $this->scriptRunner('service_name', $script, 'GET', $params, "after");
	$this->assertEquals("sample message", $output["message"]); 
  }
  
  
   public function testBeforeUpdating()
   {
	$params = ["table"=>["products"]];
	$script = '->beforeUpdating()->stopAndOutput(419, "sample message", [])';
	$output = $this->scriptRunner('service_name', $script, 'PATCH', $params);
	$this->assertEquals("sample message", $output["payload"]["message"]); 
  }
  

  
   public function testAfterUpdating()
   {
	$params = ["table"=>["products"]];
	$script = '->afterUpdating()->onUpdate()->stopAndOutput(419, "sample message", [])';
	$output = $this->scriptRunner('service_name', $script, 'PATCH', $params, 'after');
	$this->assertEquals("sample message", $output["message"]); 
  }
  
   
   public function testBeforeDeleting()
   {
	$params = ["table"=>["products"]];
	$script = '->beforeDeleting()->stopAndOutput(419, "sample message", [])';
	$output = $this->scriptRunner('service_name', $script, 'DELETE', $params);
	$this->assertEquals("sample message", $output["payload"]["message"]); 
  }
 
 
   public function testAfterDeleting()
   {
	$params = ["table"=>["products"]];
	$script = '->afterDeleting()->onDelete()->stopAndOutput(419, "sample message", [])';
	$output = $this->scriptRunner('service_name', $script, 'DELETE', $params, 'after');
	$this->assertEquals("sample message", $output["message"]); 
  }
 
 
   public function testbeforeCreating()
   {
	$params = ["table"=>["products"]];
	$script = '->beforeCreating()->stopAndOutput(419, "sample message", [])';
	$output = $this->scriptRunner('service_name', $script, 'POST', $params);
	$this->assertEquals("sample message", $output["payload"]["message"]); 
  }


   public function testAfterCreating()
   {
	$params = ["table"=>["products"]];
	$script = '->afterCreating()->stopAndOutput(419, "sample message", [])';
	$output = $this->scriptRunner('service_name', $script, 'POST', $params, 'after');
	$this->assertEquals("sample message", $output["message"]); 
  }

   public function testonAnyRequest()
   {
	$params = ["table"=>["products"]];
	$script = '->onAnyRequest()->stopAndOutput(419, "sample message", [])';
	$output = $this->scriptRunner('service_name', $script, 'POST', $params, 'after');
	$this->assertEquals("sample message", $output["message"]); 
  }
      
   private function scriptRunner($service, $script, $method, $params, $phase = 'before')
   {
       $rulesInstance = $this->rules;
       $scriptEngine = $this->ScriptHandler;
       
       $compiled = $scriptEngine->compile_script($script);
       $samplePayload =  $this->getSamplePayload('service_name', $compiled['var_init'], $compiled['script'], 
       null, $method, $params, $phase);

       $scriptResults = $scriptEngine->run_script('db', $samplePayload); 
       
       return $scriptResults;
   }
   private function getSamplePayload($service_name, $script_init_vars, $script, 
    $resource_access_right, $method, $params, $request_type='before'){
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

