<?php



/**
 * Created by Devless.
 * User: eddymens
 * Date Created: 23rd of September 2016 09:01:20 AM
 * @Service: event
 * @Version: 1.0
 */


//Action method for serviceName
class devless
{
    public $serviceName = 'event';

    /**
     * Sample method accessible to via endpoint
     * @ACL public
     */
    public function methodOne()
    {
        return "Sample Protected Method";
    }


    /**
     * Sample method accessible only by authenticated users
     * @ACL protected
     */
    public function methodTwo()
    {
        return "Sample Protected Method";
    }

    /**
     * Sample method not accessible via endpoint
     * @ACL public
     */
    public function methodThree()
    {
        return "Sample Protected Method";
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

