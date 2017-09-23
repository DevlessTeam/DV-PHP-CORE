<?php

use Devless\RulesEngine\Rules;
use Devless\Script\ScriptHandler;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class mathLib extends TestCase
{

 public $rules = null;

 public function __construct()
 {

   $this->rules = new Rules();
}	
   
   public function testCalculate()
   {
	$this->rules->calculate(3+5);
	$this->assertEquals(8, $this->rules->results); 
  }

  public function testSumUp() 
  {
	$this->rules->sumUp(1,2,3);
	$this->assertEquals(6, $this->rules->results);	
  }
  
 public function testSubtract()
 {
	$this->rules->subtract(3,1);
	$this->assertEquals(2, $this->rules->results);	
	$this->rules->from(5)->subtract(3);
	$this->assertEquals(2, $this->rules->results);
 }

 public function testMultiply()
 {
	$this->rules->multiply(3,3);
	$this->assertEquals(9, $this->rules->results);
 }

public function testDivide()
{
	$this->rules->divide(9,3);
	$this->assertEquals(3, $this->rules->results);
	$this->rules->assign(4)->to($number)->divideBy(2);
	$this->assertEquals(2, $this->rules->results);
}

public function testFindSquareRootOf()
{ 
	$this->rules->findSquareRootOf(4);
	$this->assertEquals(2, $this->rules->results);
	$this->rules->calculate(4)->getSquareRoot();
	$this->assertEquals(2, $this->rules->results);
}

public function testRoundUp()
{
	$this->rules->roundUp(2.1234);
	$this->assertEquals(2.1, $this->rules->results);
}

public function testPercentageOf()
{
	$this->rules->calculate(50)->percentOf(100);
	$this->assertEquals(50, $this->rules->results);
}
}
