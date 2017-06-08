<?php

use App\Helpers\Assert;

class assertTest extends TestCase
{
    private $methods = [
        array('name'=>'aString', 'params'=>array('string'), 'state'=>true),
        array('name'=>'aString', 'params'=>array(12), 'state'=>false),
        array('name'=>'aString', 'params'=>array(''), 'state'=>true),

        array('name'=>'anInteger', 'params'=>array('string'), 'state'=>false),
        array('name'=>'anInteger', 'params'=>array(1), 'state'=>true),
        array('name'=>'anInteger', 'params'=>array(''), 'state'=>false),
        array('name'=>'anInteger', 'params'=>array(1.2), 'state'=>false),

        array('name'=>'aFloat', 'params'=>array(1), 'state'=>false),
        array('name'=>'aFloat', 'params'=>array(1.2), 'state'=>true),
        array('name'=>'aFloat', 'params'=>array(''), 'state'=>false),
        array('name'=>'aFloat', 'params'=>array('string'), 'state'=>false),


        array('name'=>'aBoolean', 'params'=>array(true), 'state'=>true),
        array('name'=>'aBoolean', 'params'=>array(false), 'state'=>true),
        array('name'=>'aBoolean', 'params'=>array(''), 'state'=>false),
        array('name'=>'aBoolean', 'params'=>array('string'), 'state'=>false),
        array('name'=>'aBoolean', 'params'=>array(0), 'state'=>false),
        array('name'=>'aBoolean', 'params'=>array(1), 'state'=>false),
        array('name'=>'aBoolean', 'params'=>array('true'), 'state'=>false),
        array('name'=>'aBoolean', 'params'=>array('false'), 'state'=>false),
        
        array('name'=>'notEmpty', 'params'=>array([]), 'state'=>false),
        array('name'=>'notEmpty', 'params'=>array([1,2]), 'state'=>true),

       
        
        
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
