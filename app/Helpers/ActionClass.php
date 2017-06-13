<?php
namespace App\Helpers;
use Devless\SDK\SDK;
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
        $serviceMethodPath = (strtolower($service) == config('devless')['name']) ?
                            config('devless')['system_class'] :
                            config('devless')['views_directory'].$service.'/ActionClass.php';

         
        /** @var TYPE_NAME $serviceMethodPath */
        (file_exists($serviceMethodPath))?
            require_once $serviceMethodPath : false;
        
        $serviceInstance = new $service();
        $results = $serviceInstance->$method(...$params);

        return $results;
    }

    /**
     * execute action methods from other DevLess instances
     * @param $service
     * @param $method
     * @param null $params
     * @param $url
     * @param $token
     * @return mixed|string
     */
    public static function remoteExecute($service, $method, $params = null, $url, $token )
    {
        $devless = new SDK($url, $token);
        return $devless->call($service, $method, $params);

    }
}
