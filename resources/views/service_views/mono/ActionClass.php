<?php
/**
 * Created by Devless.
 * Author: eddymens
 * Date Created: 23rd of June 2017 11:02:02 AM
 * Service: mono
 * Version: 1.3.0
 */

use App\Helpers\ActionClass;
//Action method for serviceName
class mono
{
    public $serviceName = 'mono';

    /**
     * Sample Method showing how `@ACL private` prevents people from calling on the method from any of the DevLess SDKs.
     * @ACL private
     */
    public function samplePrivateMethod()
    {
        return "Private Hello";
    }


    /**
     * Methods decorated with `@ACL protected` are only available to users who are logged in . You may access the method via any of the SDKs.
     * @ACL protected
     */
    public function sampleProtectedMethod()
    {
        return "Protected Hello";
    }

    /**
     * Sample public method can be acceessed by any user from any of the SDKs due to the `@ACL public decoration.
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

        return $actionClass->help($serviceInstance, $methodToGetDocsFor);   
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

