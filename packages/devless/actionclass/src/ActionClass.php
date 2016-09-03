<?php
namespace Devless\ActionClass;
/**
 * Created by PhpStorm.
 * User: eddymens
 * Date: 02/09/2016
 * Time: 12:45 PM
 */



class ActionClass
{
    /**
     * @param $service
     * @param $method
     * @return mixed
     */
    public function execute($service, $method, $parameters)
    {
        $serviceMethodPath = config('devless')['views_directory'].$service.'/ActionClass.php';

        (file_exists($serviceMethodPath))?
                require_once $serviceMethodPath : false;


        $serviceInstance = new $service();
        return $serviceInstance->$method();
    }
}