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


