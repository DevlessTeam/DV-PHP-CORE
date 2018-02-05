<?php

use Devless\RulesEngine\Rules;
use Devless\Script\ScriptHandler;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class collectionLib extends TestCase
{

    public $rules = null;

    public function __construct()
    {

        $this->rules = new Rules();
    }
   
    public function testfromTheCollectionOf()
    {
        $this->rules->fromTheCollectionOf(["name"=>"mike", "age"=>29])->getAllKeys();
     
        $this->assertEquals(["name", "age"], $this->rules->results);
    }

    public function testCollect()
    {
        $this->rules->collect(["name"=>"mike", "age"=>29])->getAllKeys();
     
        $this->assertEquals(["name", "age"], $this->rules->results);
    }

    public function testGetValuesWithoutKeys()
    {
        $this->rules->collect(["name"=>"mike", "age"=>29])->getValuesWithoutKeys()->storeAs($values)->stopAndOutput(1000, "got response", $values);
     
        $this->assertEquals(["mike",29], $this->rules->results);
    }

    public function testGetAllKeys()
    {
        $this->rules->fromTheCollectionOf(["name"=>"mike", "age"=>29])->getAllKeys()->storeAs($keys);
        $this->assertEquals(["name","age"], $this->rules->results);
    }

    public function testAppendCollectionTo()
    {
        $this->rules->collect(["name"=>"mike", "age"=>29])->appendCollectionTo($superArray = [["id"=>1, "name"=>"sam"],["id"=>2, "name"=>"josh"]], $subArray = [["id"=>2, "age"=>20],["id"=>1,"age"=>12]], $superKey = "id", $subKey = "id", $resultingKey = "result");

        $this->assertEquals('[{"id":1,"name":"sam","result":[{"id":1,"age":12}],"related":[]},{"id":2,"name":"josh","result":[{"id":2,"age":20}],"related":[]}]', json_encode($this->rules->results));
    }

    public function testGetElement()
    {
        $this->rules->collect(["Joe", "Sam", "Mike"])->getElement(1);
        $this->assertEquals('Joe', $this->rules->results);
    }

    public function testGetLastElement()
    {
        $this->rules->collect(["Joe", "Sam", "Mike"])->getLastElement();
        $this->assertEquals('Mike', $this->rules->results);
    }
   
    public function testCountTheNumberOfElements()
    {
        $this->rules->collect(["Joe", "Sam", "Mike"])->countTheNumberOfElements();

        $this->assertEquals(3, $this->rules->results);
    }

    public function testFetchAllWith()
    {
        $this->rules->collect([["item"=>"soap", "quantity"=>5],["item"=>"milk", "quantity"=>3],["item"=>"book", "quantity"=>5]])->fetchAllWithout("quantity", 5);

        $this->assertEquals('[{"item":"milk","quantity":3}]', json_encode($this->rules->results));
    }
  
    public function testFetchAllWithout()
    {
        $this->rules->collect([["item"=>"soap", "quantity"=>5],["item"=>"milk", "quantity"=>3],["item"=>"book", "quantity"=>5]])->fetchAllWithout("quantity", 5);

        $this->assertEquals('[{"item":"milk","quantity":3}]', json_encode($this->rules->results));
    }

    public function testFetchOnly()
    {
        $this->rules->collect([["item"=>"soap", "quantity"=>5],["item"=>"milk", "quantity"=>3],["item"=>"book", "quantity"=>5]])->fetchOnly("quantity");
        
        $this->assertEquals([5,3,5], $this->rules->results);
    }

    public function testApply()
    {
        $this->rules->collect(["Joe", "Mike"])->apply("convertToUpperCase", $params = []);
        $this->assertEquals(["JOE","MIKE"], $this->rules->results);
    }

    public function testApplyOnElement()
    {
        $this->rules->collect([["name"=>"Joe", "age"=>12],["name"=>"Mark", "age"=>23]])->applyOnElement("convertToUpperCase", "name" );

        $this->assertEquals([["name"=>"JOE", "age"=>12],["name"=>"MARK", "age"=>23]], $this->rules->results);
    }

    public function testReverseTheCollection()
    {
        $this->rules->collect(["Joe", "Mike"])->reverseTheCollection();
        $this->assertEquals(["Mike","Joe"], $this->rules->results);
    }

    public function testSortCollectionBy()
    {
        $this->rules->collect(["Zina", "Adam"])->sortCollectionBy("name");
        $this->assertEquals(["Adam","Zina"], $this->rules->results);
    }

    public function testOffsetCollectionBy() 
    {
       $this->rules->collect(["Adam", "Ben", "Zina"])->offsetCollectionBy(1);
       $this->assertEquals(["Ben","Zina"], $this->rules->results);

    }
}
