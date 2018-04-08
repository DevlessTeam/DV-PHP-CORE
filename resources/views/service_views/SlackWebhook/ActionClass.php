<?php
/**
 * Created by Devless.
 * Author: gbevu
 * Date Created: 7th of April 2018 01:56:07 PM
 * Service: SlackWebhook
 * Version: 1.3.6
 */

use App\Helpers\ActionClass;
use App\Helpers\DataStore;
//Action method for serviceName
class SlackWebhook
{
    private $serviceName = 'SlackWebhook';
    private $tableName = 'config';

    /**
     * Sample Method showing how `@ACL private` prevents people from calling on the method from any of the DevLess SDKs.
     * @ACL private
     */
    public function send($name, $body)
    {
        $config = DataStore::service($this->serviceName, $this->tableName)->where('name', $name)->queryData();
        if ($config['payload']['properties']['current_count'] != 0) {
            $config = $config['payload']['results'][0];
            $payload = array(
                'text' => $body
            );
            return $this->requestProcessor($config->webhook_url, json_encode($payload));
        }
        return "Config for the webhook not found";
    }

    /**
     * List out all possible callable methods as well as get docs on specific method 
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

    private function requestProcessor ($url, $body) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }


}

