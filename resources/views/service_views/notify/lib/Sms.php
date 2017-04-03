<?php
/**
 * Created by Devless.
 * Author: koficodes
 * Date Created: 20th of January 2017 02:52:24 AM
 * @Service: notify
 * @Version: 1.0
 */
namespace Notify;

require 'Twilio/autoload.php';


use Notify\Config;
use Notify\NotifyBaseClass;
use Twilio\Rest\Client;

/**
 * @Sends sms to users using twilio API. www.twilio.com
 */
class Sms extends NotifyBaseClass
{
    private $account_sid;
    private $auth_token;
    private $senderNumber;

    public function __construct()
    {
        $smsConfig = Config::get('sms');
        $this->account_sid = $smsConfig['accountId'];
        $this->auth_token = $smsConfig['authToken'];
        $this->senderNumber = $smsConfig['senderNumber'];

    }

     /**
     * @description {sends sms to users}
     * @param      <Array> <$recipient> { array of user numbers }
     * @param      <String> <$messageBody> { message body of sms }
     */
    public function send($recipients = [], $messageBody = '')
    {
        
        $client = new Client($this->account_sid, $this->auth_token);
        foreach ($recipients as $key => $recipientNumber) {

            $message = $client->messages->create(
                 $recipientNumber, // Text this number
                array(
                    'from' => $this->senderNumber, // From a valid Twilio number
                    'body' => $messageBody,
                )
            );
        }


    }

    /**
     * @description {sends sms to users with selected user infomation}
     * @param      <Array> <$recipient> { array of user's with numbers }
     * @param      <String> <$recipientsNumberColumn> { table column contain user number }
     * @param      <String> <$message> { message body of sms }
     */
    public function sendWithInfo($recipients = [],$recipientNumberColumn='', $messageBody = '')
    {
         
        $client = new Client($this->account_sid, $this->auth_token);
        foreach ($recipients as $key => $recipient) {

            $message = $client->messages->create(
                 $recipient[$recipientNumberColumn], // Text this number
                array(
                    'from' => $this->senderNumber, // From a valid Twilio number
                    //replaces strings with coresponding user information from the table
                    'body' => strtr($messageBody,array_combine(
    array_map(function($k){ return '@'.$k; }, array_keys($recipient)),
    $recipient
)),
                )
            );
        }

    }
}
