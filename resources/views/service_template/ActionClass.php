<?php



{{MAINDOC}}

//Action method for serviceName
class {{ServiceName}}
{
    public $serviceName = '{{ServiceName}}';

    /**
     * Sample method accessible to via endpoint
     * @ACL private
     */
    public function methodone()
    {
        return "Sample Protected Method";
    }


    /**
     * Sample method accessible only by authenticated users
     * @ACL protected
     */
    public function methodtwo()
    {
        return "Sample Protected Method";
    }

    /**
     * Sample method not accessible via endpoint
     * @ACL public
     */
    public function methodthree()
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

