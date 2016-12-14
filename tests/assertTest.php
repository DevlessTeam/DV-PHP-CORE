<?php

use App\Helpers\Assert;

class assertTest extends TestCase
{
    private $methods = [
        array('name'=>'string', 'params'=>array('string'), 'state'=>true),
        array('name'=>'string', 'params'=>array(12), 'state'=>false),
        array('name'=>'string', 'params'=>array(''), 'state'=>true),

        array('name'=>'stringNotEmpty', 'params'=>array(''), 'state'=>false),
        array('name'=>'stringNotEmpty', 'params'=>array('string'), 'state'=>false),

        array('name'=>'integer', 'params'=>array('string'), 'state'=>false),
        array('name'=>'integer', 'params'=>array(1), 'state'=>true),
        array('name'=>'integer', 'params'=>array(''), 'state'=>false),
        array('name'=>'integer', 'params'=>array(1.2), 'state'=>false),

        array('name'=>'float', 'params'=>array(1), 'state'=>false),
        array('name'=>'float', 'params'=>array(1.2), 'state'=>true),
        array('name'=>'float', 'params'=>array(''), 'state'=>false),
        array('name'=>'float', 'params'=>array('string'), 'state'=>false),

        array('name'=>'numeric', 'params'=>array(1), 'state'=>true),
        array('name'=>'numeric', 'params'=>array(1.2), 'state'=>true),
        array('name'=>'numeric', 'params'=>array(''), 'state'=>false),
        array('name'=>'numeric', 'params'=>array('string'), 'state'=>false),

        array('name'=>'boolean', 'params'=>array(true), 'state'=>true),
        array('name'=>'boolean', 'params'=>array(false), 'state'=>true),
        array('name'=>'boolean', 'params'=>array(''), 'state'=>false),
        array('name'=>'boolean', 'params'=>array('string'), 'state'=>false),
        array('name'=>'boolean', 'params'=>array(0), 'state'=>false),
        array('name'=>'boolean', 'params'=>array(1), 'state'=>false),
        array('name'=>'boolean', 'params'=>array('true'), 'state'=>false),
        array('name'=>'boolean', 'params'=>array('false'), 'state'=>false),

        array('name'=>'scalar', 'params'=>array('1'), 'state'=>true),
        array('name'=>'scalar', 'params'=>array([1,2]), 'state'=>false),

        array('name'=>'isEmpty', 'params'=>array([]), 'state'=>true),
        array('name'=>'isEmpty', 'params'=>array([1,2]), 'state'=>false),

        array('name'=>'isEmpty', 'params'=>array([]), 'state'=>true),
        array('name'=>'isEmpty', 'params'=>array([1,2]), 'state'=>false),

        array('name'=>'notEmpty', 'params'=>array([]), 'state'=>false),
        array('name'=>'notEmpty', 'params'=>array([1,2]), 'state'=>true),

        array('name'=>'isNull', 'params'=>array(null), 'state'=>true),
        array('name'=>'isNull', 'params'=>array([1,2]), 'state'=>false),

        array('name'=>'notNull', 'params'=>array(null), 'state'=>false),
        array('name'=>'notNull', 'params'=>array([1,2]), 'state'=>true),

        array('name'=>'true', 'params'=>array(true), 'state'=>true),
        array('name'=>'true', 'params'=>array(false), 'state'=>false),
        array('name'=>'true', 'params'=>array(''), 'state'=>false),

        array('name'=>'false', 'params'=>array(false), 'state'=>false),
        array('name'=>'false', 'params'=>array(true), 'state'=>true),
        array('name'=>'false', 'params'=>array(''), 'state'=>true),
        
        array('name'=>'eq', 'params'=>array("string", "string"), 'state'=>true),
        array('name'=>'eq', 'params'=>array("string", "notString"), 'state'=>false),
        
        array('name'=>'notEq', 'params'=>array('string', 'string'), 'state'=>false),
        array('name'=>'notEq', 'params'=>array('string', 'notString'), 'state'=>true),

        array('name'=>'same', 'params'=>array('string', 'string'), 'state'=>true),
        array('name'=>'same', 'params'=>array('string', 'notString'), 'state'=>false),

        array('name'=>'notSame', 'params'=>array('string', 'string'), 'state'=>false),
        array('name'=>'notSame', 'params'=>array('string', 'notString'), 'state'=>true),
        
        
    ];

    /**
     * Test Assertions.
     *
     * @return void
     */
    public function testMethod()
    {

        foreach ($this->methods as $method ) {
            $state = forward_static_call_array(['App\Helpers\Assert', $method['name']], $method['params']);

            $this->assertEquals($method['state'], $state);
        }

    }
}
