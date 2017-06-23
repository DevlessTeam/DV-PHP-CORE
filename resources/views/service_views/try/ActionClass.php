<?php
/**
 * Created by Devless.
 * Author: eddymens
 * Date Created: 23rd of June 2017 03:14:30 AM
 * Service: try
 * Version: 1.3.0
 */


//Action method for serviceName
class try
{
    public $serviceName = 'try';

    /**
     * Method cannnot be accessed via any SDK
     * @ACL private
     */
    public function methodone()
    {
        return "Sample Private Method";
    }


    /**
     * Method accessible only by authenticated users
     * @ACL protected
     */
    public function methodtwo()
    {
        return "Sample Protected Method";
    }

    /**
     * Method is accessible via any DevLess SDK
     * @ACL public
     */
    public function methodthree()
    {
        return "Sample Public Method";
    }

    public function help()
    {
            
            $allMethods = get_class_methods($this);
            
            $exemptedMethods = [];
            $methodList = [];
            $rules = new Rules();
            foreach ($allMethods as $methodName) {
                if(!in_array($methodName, $exemptedMethods)){
                    $method = new \ReflectionMethod($rules, $methodName); 
                    $methodDocs = str_replace("*/","",$method->getDocComment());
                    $methodDocs = str_replace("/**","",$methodDocs);
                    $methodDocs = str_replace("* *","||",$methodDocs);
                    array_push($methodList, $methodName.' :  '.$methodDocs);
                }
            }
            
            $this->stopAndOutput(1000, 'List of all callable methods', $methodList);
            return $this;

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

