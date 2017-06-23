<?php
/**
 * Created by Devless.
 * Author: eddymens
 * Date Created: 10th of June 2017 12:12:14 AM
 * Service: trail
 * Version: 1.3.0
 */


//Action method for serviceName
class trail
{
    public $serviceName = 'trail';

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

