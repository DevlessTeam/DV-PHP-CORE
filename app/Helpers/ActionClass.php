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
     * @param mixed  $params
     * @return mixed
     */
    public static function execute($service, $method, $params = null)
    {
        $serviceMethodPath = (strtolower($service) == config('devless')['name']) ?
                            config('devless')['system_class'] :
                            config('devless')['views_directory'].$service.'/ActionClass.php';

         
        /**
 * @var TYPE_NAME $serviceMethodPath
*/
        (file_exists($serviceMethodPath))?
            include_once $serviceMethodPath : false;
        
        $serviceInstance = new $service();
        
        $results = $serviceInstance->$method(...$params);


        return $results;
    }

    /**
     * execute action methods from other DevLess instances
     * @param $service
     * @param $method
     * @param null    $params
     * @param $url
     * @param $token
     * @return mixed|string
     */
    public static function remoteExecute($service, $method, $params = null, $url, $token)
    {
        $devless = new SDK($url, $token);
        return $devless->call($service, $method, $params);
    }


    /**
     * List out all possible callbale methods as well as get docs on specific method eg: ->help('stopAndOutput')
     * @param $methodToGetDocsFor
     * @return $this;
     */
    public function help($serviceInstance, $methodToGetDocsFor)
    {

        
        $methods = get_class_methods($serviceInstance);
        
        $exemptedMethods = ['__construct','requestType','__call','useArgsOrPrevOutput','executor','commonMutationTask'];

        $methodList = [];
        
        $getMethodDocs = function ($methodName) use ($exemptedMethods, $serviceInstance) {
            if (!in_array($methodName, $exemptedMethods)) {
                $method = new \ReflectionMethod($serviceInstance, $methodName);
                $methodDocs = str_replace("*/", "", $method->getDocComment());
                $methodDocs = str_replace("/**", "", $methodDocs);
                return $methodDocs = str_replace("* *", "||", $methodDocs);
            } else {
                return false;
            }
        };

        if ($methodToGetDocsFor) {
            $docs = $getMethodDocs($methodToGetDocsFor);
            if ($docs) {
                 $methodList[$methodToGetDocsFor] = $docs;
            }
        } else {
            foreach ($methods as $methodName) {
                $methodDocs = $getMethodDocs($methodName);
                if ($methodDocs) {
                    $methodList[$methodName] = $methodDocs;
                }
            }
        }
        
        
        return [1000, 'List of callable methods', $methodList];
    }
}
