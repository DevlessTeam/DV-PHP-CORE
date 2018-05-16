<?php
/**
 * Created by Devless.
 * Author: Add username here
 * Date Created: 15th of May 2018 11:50:48 AM
 * Service: testSuit
 * Version: 1.3.6
 */

use App\Helpers\ActionClass;
use App\Helpers\DataStore as ds;

//Action method for serviceName
class testSuit
{
    public $serviceName = 'testSuit';

    /**
     * @ACL private
     */
    public function getServices()
    {
        return DB::table('services')->get();
    }


    /**
     * @ACL private
     */
    public function getScript($service)
    {
        $script = ds::service($this->serviceName, 'tests')->where('name',$service)->getData();
        if(!$script['payload']['results']){
            echo "console.log('edmond')";
            return;
        }
        echo ($script['payload']['results'][0]->test);
        return;
    }

    /**
     * @ACL private
     */
    public function save($service, $script)
    {
        $output =  ds::service($this->serviceName, 'tests')
        ->where('name', $service)->update(["test"=> $script]);
        
        if ($output['status_code'] == 629) {
            $output =  ds::service($this->serviceName, 'tests')->addData([["name"=> $service, "test"=> $script]]);
        }

        return $output;
    }

    /**
     * Sample public method can be accessed by any user from any of the SDKs due to the `@ACL public decoration.
     * @ACL public
     */
    public function samplePublicMethod()
    {
        return "Public Hello";
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
