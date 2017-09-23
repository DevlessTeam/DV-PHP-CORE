
<?php

use Devless\RulesEngine\Rules;
use Devless\Script\ScriptHandler;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class generators  extends TestCase
{

 public $rules = null;

 public function __construct()
 {

   $this->rules = new Rules();
}	
   
   public function testgenerateRandomInteger()
   {
	
	$this->rules->generateRandomInteger(5);
	$output = $this->rules->results; 
	$this->assertTrue(true, ($this->rules->results>1)); 
	$this->assertEquals(1, count($this->rules->results)); 
  }

  public function testRandomAlphanums()
  {
	$this->rules->generateRandomAlphanums(2);
	$this->assertEquals("string", gettype($this->rules->results) );
	$this->assertEquals(2, strlen($this->rules->results));	
  }

 
  public function testRandomString()
  {
	$this->rules->generateRandomString(2);
	$this->assertEquals("string", gettype($this->rules->results) );
	$this->assertEquals(2, strlen($this->rules->results));	
  }
 
  public function testGenerateUniqueId()
  {
	$this->rules->generateUniqueId();
	$this->assertEquals("string", gettype($this->rules->results));
	$this->assertTrue( ( strlen($this->rules->results) > 0 ) );
  }

  
}
