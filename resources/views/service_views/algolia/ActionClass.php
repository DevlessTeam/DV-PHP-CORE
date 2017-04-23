<?php
/**
 * Created by Devless.
 * Author: eddymens
 * Date Created: 23rd of March 2017 02:10:35 PM
 * Service: algolia
 * Version: 1.2.7
 */
 
use App\Helpers\DataStore as DS 

//Action method for serviceName
class algolia
{
    public $serviceName = 'algolia';

    /**
     * Method cannnot be accessed via any SDK
     * @ACL private
     */
    public function set_keys($app, $token)
    {

        return "Sample Private Method";
    }


    /**
     * Method accessible only by authenticated users
     * @ACL protected
     */
    public function get_keys()
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

