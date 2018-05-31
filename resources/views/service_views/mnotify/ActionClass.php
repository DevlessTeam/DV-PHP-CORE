<?php
/**
 * Created by Devless.
 * Author: Add username here
 * Date Created: 20th of September 2017 04:38:21 PM
 * Service: mnotify
 * Version: 1.3.2
 */

use App\Helpers\ActionClass;
use App\Helpers\DataStore;
//Action method for serviceName
class mnotify
{
    public $serviceName = 'mnotify';

    /**
     * Sample Method showing how `@ACL private` prevents people from calling on the method from any of the DevLess SDKs.
     * @ACL private
     */
    public function sendSMS($sender_id, $message, $phone)
    {

        $numbers = array_slice(func_get_args(), 2);
        
        $acc_details = DataStore::service('mnotify', 'settings')->getData()['payload']['results'];
        if(! isset($acc_details[0])){
            return 'You need to set your API Key, do this by clicking on DOCS:UI from the mnotify service';
        }
        $key= $acc_details[0]->api_key; //your unique API key;

        $message=urlencode($message); //encode url;
        
        foreach ($numbers as $number) {
            # code...
            $url="https://apps.mnotify.net/smsapi?key=$key&to=$number&msg=$message&sender_id=$sender_id";
            $result=file_get_contents($url);
        }

        return $result;
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

