<?php

use App\Helpers\Assert;

class assertTest extends TestCase
{
    private $methods = [
        ['name'=>'aString', 'params'=>['string'], 'state'=>true],
        ['name'=>'aString', 'params'=>[12], 'state'=>false],
        ['name'=>'aString', 'params'=>[''], 'state'=>true],

        ['name'=>'anInteger', 'params'=>['string'], 'state'=>false],
        ['name'=>'anInteger', 'params'=>[1], 'state'=>true],
        ['name'=>'anInteger', 'params'=>[''], 'state'=>false],
        ['name'=>'anInteger', 'params'=>[1.2], 'state'=>false],

        ['name'=>'aFloat', 'params'=>[1], 'state'=>false],
        ['name'=>'aFloat', 'params'=>[1.2], 'state'=>true],
        ['name'=>'aFloat', 'params'=>[''], 'state'=>false],
        ['name'=>'aFloat', 'params'=>['string'], 'state'=>false],


        ['name'=>'aBoolean', 'params'=>[true], 'state'=>true],
        ['name'=>'aBoolean', 'params'=>[false], 'state'=>true],
        ['name'=>'aBoolean', 'params'=>[''], 'state'=>false],
        ['name'=>'aBoolean', 'params'=>['string'], 'state'=>false],
        ['name'=>'aBoolean', 'params'=>[0], 'state'=>false],
        ['name'=>'aBoolean', 'params'=>[1], 'state'=>false],
        ['name'=>'aBoolean', 'params'=>['true'], 'state'=>false],
        ['name'=>'aBoolean', 'params'=>['false'], 'state'=>false],
        
        ['name'=>'notEmpty', 'params'=>[[]], 'state'=>false],
        ['name'=>'notEmpty', 'params'=>[[1,2]], 'state'=>true],

       
        
        
    ];

    /**
     * Test Assertions.
     *
     * @return void
     */
    public function testMethod()
    {

        foreach ($this->methods as $method) {
            $state = forward_static_call_array(['App\Helpers\Assert', $method['name']], $method['params']);

            $this->assertEquals($method['state'], $state);
        }
    }
}
