<?php

namespace App\Helpers;

class ActionClass
{

    public static function execute($service, $method, $params = null)
    {

        $serviceMethodPath = config('devless')['views_directory'].$service.'/ActionClass.php';

        (file_exists($serviceMethodPath))?
            require_once $serviceMethodPath : false;


        $serviceInstance = new $service();
        $results = $serviceInstance->$method($params);

        return $results;
    }
}


//
//
//class Foo {
//    private $methods = array();
//
//    public function addBar() {
//        $barFunc = function () {
//            var_dump($this->methods);
//        };
//        $this->methods['bar'] = \Closure::bind($barFunc, $this, get_class());
//    }
//
//    function __call($method, $args) {
//        if(is_callable($this->methods[$method]))
//        {
//            return call_user_func_array($this->methods[$method], $args);
//        }
//    }
//}
//
//$foo = new Foo;
//$foo->addBar();
//$foo->bar();
