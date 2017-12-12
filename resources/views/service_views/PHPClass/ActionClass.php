<?php
/**
 * Created by Devless.
 * Author: Eddymens
 * Date Created: 11th of December 2017 12:29:17 PM
 * Service: PHPClass
 * Version: 1.3.5
 */
use App\Helpers\Helper;
use App\Helpers\ActionClass;
use App\Helpers\DataStore as ds;

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use Devless\Script\Transverser;

//Action method for serviceName
class PHPClass
{
    public $serviceName = 'PHPClass';

    /**
     * Methods decorated with `@ACL protected` are only available to users who are logged in . You may access the method via any of the SDKs.
     * @ACL private
     */
    public function save($script)
    {
        $result = $this->compile_script($script);
        if(!$result['successful']){
            return $result['error_message'];
        }
        $script = preg_replace('/^<\?php(.*)(\?>)?$/s', '$1', $script);
        $output = ds::service($this->serviceName, 'class')
            ->where('id',1)->update(["script"=> $script]);
        
        return $output;
    }


    public function getCode()
    {
        $output = ds::service($this->serviceName, 'class')->getData();
        return ($output['payload']['properties']['count'])?
                $output['payload']['results'][0]->script:'<?php
class sampleClass {
     public function hello()
     {
         return "HelloWorld"; 
     }
}
';     
        
    }

    /**
     * Sample public method can be accessed by any user from any of the SDKs due to the `@ACL public decoration.
     * @ACL private
     */
    public function execute($class, $method, $args=[])
    {
        $output = ds::service($this->serviceName, 'class')->getData();

        ($output['payload']['properties']['count'])?eval($output['payload']['results'][0]->script):"";     
        
        (strpos(error_get_last()['file'], 'ActionClass.php') !=false)?dd():'';

        return (new $class())->{$method}(...$args);
    }

    /**
     * List out all possible callbale methods as well as get docs on specific method 
     * @param $methodToGetDocsFor
     * @return $this;
     */
    public function help($methodToGetDocsFor=null)
    {
        $serviceInstance = $this;
        $actionClass = new ActionClass();
        return $actionClass->help($serviceInstance, $methodToGetDocsFor=null);   
    }


     public function compile_script($code)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $traverser->addVisitor(new Transverser);
        $prettyPrinter = new PrettyPrinter\Standard;
        try {
            $stmts = $parser->parse($code);
            $stmts = $traverser->traverse($stmts);
            $code = substr($prettyPrinter->prettyPrint($stmts), 6);
        } catch (Error $e) {
            $compiled_script['error_message'] = $e->getMessage();
            $compiled_script['successful'] = false;
            return $compiled_script;
        }
        
        $compiled_script['script'] = $code;
        $compiled_script['successful'] = true;
        return $compiled_script;
    }
    /**
     * This method will execute on service importation
     * @ACL private
     */
    public function __onImport()
    {
        //add code here

    }


    /**
     * This method will execute on service exportation
     * @ACL private
     */
    public function __onDelete()
    {
        //add code here

    }


}




