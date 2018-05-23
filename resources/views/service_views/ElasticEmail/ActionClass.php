<?php
/**
 * Created by Devless.
 * Author: edwin
 * Date Created: 22nd of May 2018 04:26:37 PM
 * Service: ElasticEmail
 * Version: 1.3.6
 */
use App\Helpers\DataStore;
use App\Helpers\ActionClass;
//Action method for serviceName
class ElasticEmail
{
    public $serviceName = 'ElasticEmail';

    private $config = null;

    private $emails = "";

    /**
     * send transactional email.
     * @ACL public
     */
    public function send($entity_id, $to, $template, $body)
    {
        $this->getConfig($entity_id);
        if ($this->isArr($body) && $this->isMultipleEmail($to) && $this->isString($template)) {
            $params = $this->emails."&template=".$template;
            foreach ($body as $index => $value) {
                $params .= "&merge_$index=".urlencode(trim(preg_replace('/\s\s+/', '', $value)));
            }
            return $this->requestProcessor($params);
        }
        return "Please check the parameters passed";
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

    private function isArr($body)
    {
        if(is_array($body)) {
            return true;
        }
        exit($body.' is not an array.');
    }

    private function isEmail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //The email address is valid.
            $this->emails .= "&to=$email";
            return true;
        }
        exit("The recipient $email is not valid");
    }

    private function isMultipleEmail($email)
    {
        if(is_array($email)) {
            foreach ($email as $value) {
                $this->isEmail($value);
            }
            return true;
        }
        $this->isEmail($email);
        return true;
    }

    private function isString($string)
    {
        if (is_string(($string))) {
            return true;
        }
        exit('Only strings are allowed');
    }

    /**
     * This method retrieves and sets the configs for the process
     * @ACL private
     */
    private function getConfig($identifier)
    {
        $results = DataStore::service('ElasticEmail', 'config')->where('entity_id', $identifier)->queryData();
        if ($results['payload']['properties']['current_count'] > 0) {
            return $this->config = $results['payload']['results'][0];
        }
        exit('No configuration available for such indentifier');
    }

    /**
     * This method handles the process requests
     */
    private function requestProcessor($params)
    {
        $url = "https://api.elasticemail.com/v2/email/send?apikey=".trim($this->config->api_key, ' ').$params;
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
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

