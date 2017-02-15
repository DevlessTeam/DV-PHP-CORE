<?php
namespace App\Helpers;

/**
 * Internal ActionClass execution
 */
class ActionClass
{

    /**
     * execute service action class
     * @param string $service
     * @param string $method
     * @param mixed $params
     * @return mixed
     */
    public static function execute($service, $method, $params = null)
    {
        if (strtoupper($service) == 'DEVLESS') {
            $serviceMethodPath = __DIR__.'/SystemClass.php';
        } else {
            $serviceMethodPath = config('devless')['views_directory'].$service.'/ActionClass.php';
        }

        (file_exists($serviceMethodPath))?
            require_once $serviceMethodPath : false;

        $serviceInstance = new $service();
        $results = $serviceInstance->$method(...$params);
        return $results;
    }
}
