<?php

use App\Helpers\Helper;
use App\Helpers\DataStore;
use App\Helpers\DevlessHelper as DVH;
use App\Http\Controllers\ServiceController as service;

include 'assets/smsgh/Api.php';

/**
 * Created by Devless.
 * Author: edwin tsatsu
 * Date Created: 26th of January 2017 10:04:13 AM
 * @Service: TTLUSSD
 * @Version: 1.0
 */


//Action method for serviceName
class TTLUSSD
{
    public $serviceName = 'TTLUSSD';
    
    /*Mpower credentials
    private $master_key = "41bf6a2b-2a24-40d2-9b4f-0c6b56c34804";
    private $private_key = "live_private_jRvX9Ukvy40XMBJ3_6lyJ6SKUY8";
    private $token = "6ea571850455ca12ba47";
    */

    /*DeMorgan Credentials*/
    private $requestUri = "http://pie.demorgan.biz/api/mobile";
    private $appId = "7cadb36461614730a12fe9bfc92550a8";
    private $appKey = "g4HUGq0BBOeUmxPQM0g2kLH8Ak8/o9A6/YCF7E98KAI=";

    /**
     * Check mpower payment status
     * @ACL protected
     */
     /*
    public function checkstatus($token)
    {
        $url = "https://app.mpowerpayments.com/api/v1/direct-mobile/status";
        $params = "{\n\"token\":\"$token\"\n}";

        $response = $this->curlProcessor($url, $params);

        return $response;
    }


    /**
     * Sample method accessible only by authenticated users
     * @ACL protected
     */
    public function sign_in($payload)
    {
        $service['service_name'] = $this->serviceName;
        $auth  = new DVH();
        $auth_payload['username'] = $payload["username"];
        $auth_payload['password'] = $payload["password"];
        if ($user_token = $auth->login($auth_payload)) {
            $user_profile = \DB::table('users')
                ->where('username', $auth_payload['username'])->get();
                $user_profile['user_token'] = $user_token;
             \Session::put('user_profile', $user_profile[0]);
             \Session::put('user_token', $user_token);
             \Session::put("error", false);
             return DvRedirect(DvNavigate($service, 'dashboard'), 0);
        } else {
            \Session::put("error", true);
            return DvRedirect(DvNavigate($service, 'index'), 0);
        }
    }

    /**
     * Sample method not accessible via endpoint
     * @ACL public
     */
    public function logout()
    {
        $service['service_name'] = $this->serviceName;
        \Session::forget('user_profile');
        \Session::forget('error');
        \Session::forget('user_token');
        \Session::flush();
        
        return true;
    }

    /**
     * Sample method not accessible via endpoint
     * @ACL protected
     */
     public function sendRequestSMS($phone, $name)
     {   
         $message = "Hello $name, a tractor has been assigned to your request.".
            " Thank you for doing business with TTL. You can reach us on 0266800577/0572255600/0555428351";
         $this->smsProcessor("TTL", $phone, $message);
     }

    /**
     * This method will execute on service importation
     * @ACL protected
     */
     public function sendSMS($phone, $msg) 
     {  
         return $this->smsProcessor("TTL", $phone, $msg);
     }

     /*
        Run cron task for issuing payment
     public function cronTask()
     {
         $service = new service();
         $date = new DateTime();

         $url = "https://app.mpowerpayments.com/api/v1/direct-mobile/charge";

         $results = DataStore::service("ttlussd", "tractor_requests", $service)
                                ->where("payment_check", "false")
                                ->queryData();
        
        if (count($results['payload']['results'] != 0)) {

            foreach ($results['payload']['results'] as $request) {
                $params = "{ \"customer_name\" : \"$request->name\", \"customer_phone\" : \"$request->phone\",".
                            "\"customer_email\" : \"customer@trotrotractor.com\", \"wallet_provider\" : \"$request->operator\",".
                            "\"merchant_name\" : \"$request->name\", \"amount\" : \"$request->cost\" }";
                $res = $this->curlProcessor($url, $params);
                if(json_decode($res)->token != ""){

                    DataStore::service("ttlussd", "transactions", $service)->addData([
                        [
                            "date"  => $date->format('Y-m-d H:i:s'),
                            "log"   => $res
                        ]
                    ]);
                 
                    DataStore::service("ttlussd", "tractor_requests", $service)->where("id", $request->id)
                        ->update([
                            "payment_check" => "true"
                    ]); 
                }
            }
        } else {
            return false;
        }

       return true;

     }
*/

    /*
        Hatua Solutions Payment integrations
    */
    public function cronTask()
    {
        $service = new service();

        $response = DataStore::service("ttlussd", "tractor_requests", $service)
                                ->where("payment_check", "false")
                                ->queryData();

        if (count($response['payload']['results'] != 0)) {
            foreach ($response['payload']['results'] as $request) {
                $timestamp = time();
                $nonce = $this->guidv4();
                
                if (
                        preg_match("/\b024/", $request->phone) || 
                        preg_match("/\b054/", $request->phone) || 
                        preg_match("/\b055/", $request->phone)
                    ){
                        $payloadNew = '{"OrderTxnRef":"TTLC'.rand().'","TxnMode":1,"TxnType":"DR","PhoneNumber":"'
                            .$request->phone.'","TxnWallet":"MTN","MerchantTxnRef":"TTL'.rand().'","Amount":'.$request->cost.'}';
                }
                elseif(preg_match("/\b026/", $request->phone) || 
                        preg_match("/\b056/", $request->phone)){
                        $payloadNew = '{"OrderTxnRef":"TTC'.rand().'","TxnMode":1,"TxnType":"DR","PhoneNumber":"'
                            .$request->phone.'","TxnWallet":"AIR","MerchantTxnRef":"TTL'.rand().'","Amount":'.$request->cost.'}';
                } 
                else {
                        $payloadNew = '{"OrderTxnRef":"TTLC'.rand().'","TxnMode":1,"TxnType":"DR","PhoneNumber":"'
                            .$request->phone.'","TxnWallet":"TIG","MerchantTxnRef":"TTO'.rand().'","Amount":'.$request->cost.'}';
                }
                
                $hashPayload = md5($payloadNew);

                $encodedurl = strtolower(urlencode($this->requestUri));

                $rawSignature = $this->appId."POST".$encodedurl."$timestamp$nonce$hashPayload";
                
                $encSignature = hash_hmac('sha256', $rawSignature, $this->appKey, true);
                $finalSignature = base64_encode($encSignature);
                
                $res = $this->curlProcessor($this->requestUri, $payloadNew, $finalSignature, $nonce, $timestamp);
                dd($res);

                if(json_decode($res)->token != ""){

                    DataStore::service("ttlussd", "transactions", $service)->addData([
                        [
                            "date"  => $date->format('Y-m-d H:i:s'),
                            "log"   => $res
                        ]
                    ]);
                 
                    DataStore::service("ttlussd", "tractor_requests", $service)->where("id", $request->id)
                        ->update([
                            "payment_check" => "true"
                    ]); 
                }
            }

            // $payloadNew = '{"OrderID":2000,"CustomerName":"Taiseer Joudeh","ShipperCity":"Amman","IsShipped":true,"Amount":32.0,"OrderReferrence":"ODRREF"}';
            //{ OrderTxnRef = "ALHP001", TxnMode = 1, TxnType = "DR", PhoneNumber = "0263666653", TxnWallet = "AIR", MerchantTxnRef = "ODR0001", Amount =0.01}

        }

    }

     /*
        Run cron task for check transactions
     
    public function cronCheckTask()
    {
        $url = "https://app.mpowerpayments.com/api/v1/direct-mobile/status";
        $service = new service();
        $resultz = DataStore::service("ttlussd", "transactions", $service)
                            ->where("status", "unconfirmed")
                            ->queryData();
        foreach($resultz['payload']['results'] as $transaction) {
            if (count($resultz['payload']['results']) != 0) {
                $token = json_decode($transaction->log)->token;
                $params = "{\n\"token\":\"$token\"\n}";

                $resp = $this->curlProcessor($url, $params);
                $decoded_resp = json_decode($resp); 
                if ($decoded_resp->cancel_reason == null) {
                    DataStore::service("ttlussd", "transactions", $service)->where("id", $transaction->id)
                        ->update([
                            "status" => $decoded_resp->tx_status
                        ]);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        return true;
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

    /*
        SMS processor
    */
    protected function smsProcessor($sender, $receiver, $msg)
    {
         // Here we assume the user is using the combination of his clientId and clientSecret as credentials
        $auth = new BasicAuth("rbuapflp", "bkuollii");

        // instance of ApiHost
        $apiHost = new ApiHost($auth);
        $enableConsoleLog = TRUE;
        $messagingApi = new MessagingApi($apiHost, $enableConsoleLog);

        try {
            // Quick Send approach options. Choose the one that meets your requirement
            $messageResponse = $messagingApi->sendQuickMessage($sender, $receiver, $msg);

            if ($messageResponse instanceof MessageResponse) {
                return true;
            } elseif ($messageResponse instanceof HttpResponse) {
                return false;
            }

        } catch (Exception $ex) {
            error_log($ex->getTraceAsString());
            return "error";
        }
    }

    /*
        Curl Request Processor
    */
    protected function curlProcessor($url, $params, $encoded, $nonceVal, $timestamp)
    {
        
        $amx = "$this->appId:$encoded:$nonceVal:$timestamp";

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->requestUri,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $params,
        CURLOPT_HTTPHEADER => array(
            "content-type: application/json",
            "authorization: amx $amx"
        ),
        ));

        $response = curl_exec($curl);

        return $response;
    }

    // creating nonce
    private function guidv4()
    {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s%s%s%s%s%s%s', str_split(bin2hex($data), 4));
    }



}

