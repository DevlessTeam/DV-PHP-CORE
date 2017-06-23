<?php
/**
 * Created by Devless.
 * Author: Add username here
 * Date Created: 20th of March 2017 08:49:32 AM
 * Service: test_service
 * Version: 1.2.7
 */


//Action method for serviceName
class test_service
{
    public $serviceName = 'test_service';

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

