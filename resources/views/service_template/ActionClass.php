<?php

/** @NB Do not remove  **/
@DvAdminOnly();

/**
 * Created by Devless.
 * User: username
 * Date: 02/09/2016
 * Time: 11:34 AM
 */

//Action method for serviceName
class test_service
{
    public $serviceName = 'test_service';
    //method accessible to the view from the sdk

    /**
     * @ACL protected
     */
    public function actionOne()
    {
        $service = new \App\Http\Controllers\ServiceController();
        return "am open";//DS::service($this->serviceName,'ss', $service)->addData([['a'=>'charles']]);

    }

    //method only available in rule engine
    /**
     * @ACL public
     */
    protected function actionTwo()
    {

        $service = new \App\Http\Controllers\ServiceController();
        return DS::service($this->serviceName,'ss', $service)->where('id',1)->queryData();

    }

    //code here will only be executed when service is installed
    private function __start()
    {
        //code to run from here
    }

    //code here will run before service is deleted
    private function __end()
    {
        //silence is golden
    }




}

