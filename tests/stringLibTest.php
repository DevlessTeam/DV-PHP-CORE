<?php

use Devless\RulesEngine\Rules;
use Devless\Script\ScriptHandler;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class stringLibTest extends TestCase
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
     * test string concatenation
     *
     * @return void
     */
    public function testConcatenate()
    {
    	$params = ["table"=>["products"]];
		$script = '->afterQuerying()->concatenate("test1", "test2", "test3")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('test1test2test3', $output['message']);
    }

    public function testGetFirstCharacter()
    {
		$params = ["table"=>["products"]];
		$script = '->afterQuerying()->getFirstCharacter("test1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('t', $output['message']);
    }

    public function testGetSecondCharacter()
    {

		$params = ["table"=>["products"]];
		$script = '->afterQuerying()->getSecondCharacter("test1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('e', $output['message']);
    }

    public  function testGetThirdCharacter()
    {

		$params = ["table"=>["products"]];
		$script = '->afterQuerying()->getThirdCharacter("test1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('s', $output['message']);
    }

    public function testGetCharacter()
    {

		$params = ["table"=>["products"]];
		$script = '->afterQuerying()->getCharacter(4, "test1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('1', $output['message']);
    }

    public function testGetLastButOneCharacter()
    {

		$params = ["table"=>["products"]];
		$script = '->afterQuerying()->getLastButOneCharacter("test1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('t', $output['message']);
    }

    public  function testReverseString()
    {

		$params = ["table"=>["products"]];
		$script = '->afterQuerying()->reverseString("test1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('1tset', $output['message']);
    }

    public  function testFindNReplace()
    {

		$params = ["table"=>["products"]];
		$script = '->afterQuerying()->findNReplace(1, 2, "test1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('test2', $output['message']);
    }

    public  function testConvertToUpperCase()
    {
    	$params = ["table"=>["products"]];
		$script = '->afterQuerying()->convertToUpperCase("test1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('TEST1', $output['message']);

    }

    public function testConvertToLowerCase()
    {
    	$params = ["table"=>["products"]];
		$script = '->afterQuerying()->convertToLowerCase("TEST1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('test1', $output['message']);

    }

    public function testTruncateString()
    {
	  	$params = ["table"=>["products"]];
		$script = '->afterQuerying()->truncateString(5, "this is a long text")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals('th...', $output['message']);


    }


    public function testCountWords()
    {
	  	$params = ["table"=>["products"]];
		$script = '->afterQuerying()->countWords("TEST1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals(1, $output['message']);

    }

    public function testcountCharacters()
    {
	  	$params = ["table"=>["products"]];
		$script = '->afterQuerying()->countCharacters("TEST1")->storeAs($string)->stopAndOutput(419, $string, [])';
		$output = $this->scriptRunner('service_name', $script, 'GET', $params);
		$this->assertEquals(5, $output['message']);

    }

     private function scriptRunner($service, $script, $method, $params)
   {
       $rulesInstance = $this->rules;
       $scriptEngine = $this->ScriptHandler;
       
       $compiled = $scriptEngine->compile_script($script);
       $samplePayload =  $this->getSamplePayload('service_name', $compiled['var_init'], $compiled['script'], 
       null, $method, $params, 'after');

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
