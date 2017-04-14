<?php
/**
 * Created by Devless.
 * Author: koficodes
 * Date Created: 20th of January 2017 02:52:24 AM
 * @Service: notify
 * @Version: 1.0
 */

/**
 * class for emailing using sendgrid API
 * www.sendgrid.com
 */
namespace Notify;

require 'sendgrid-php/vendor/autoload.php';

use Notify\Config;

class Email extends NotifyBaseClass
{
    private $API_KEY;
    private $SENDER_NAME;
    private $SENDER_EMAIL;

    public function __construct()
    {

        $emailConfig = Config::get('email');
        $this->API_KEY = $emailConfig['api_key'];
        $this->SENDER_NAME = $emailConfig['sender_name'];
        $this->SENDER_EMAIL = $emailConfig['sender_email'];

    }

    /**
     * @description {sends email to users}
     * @param      <Array> <$recipient> { array of user emails }
     * @param      <String> <$mailsubject> { subject of email }
     * @param      <String> <$messageBody> { message body of email }
     */
    public function send($recipients = [], $mailsubject = '', $messageBody = '')
    {

        foreach ($recipients as $key => $recipient) {
            $from = new \SendGrid\Email($this->SENDER_NAME, $this->SENDER_EMAIL);
            $subject = $mailsubject;
            $to = new \SendGrid\Email("", $recipient);
            $content = new \SendGrid\Content("text/html", $messageBody);
            $mail = new \SendGrid\Mail($from, $subject, $to, $content);
            $apiKey = $this->API_KEY;
            $sg = new \SendGrid($apiKey);
            $response = $sg->client->mail()->send()->post($mail);
        }
               

    }

    /**
     * @description {sends email to users with selected user infomation}
     * @param      <Array> <$recipient> { array of user emails }
     * @param      <String> <$mailsubject> { subject of email }
     * @param      <String> <$usersEmailColumn> { string user comma seperated user infomation}
     * @param      <String> <$messageBody> { message body of email }
     */


    public function sendWithInfo($recipients = [], $mailsubject = '', $usersEmailColumn = '', $messageBody = '')
    {
        foreach ($recipients as $key => $recipient) {
            $from = new \SendGrid\Email($this->SENDER_NAME, $this->SENDER_EMAIL);
            $subject = $mailsubject;
            $apiKey = $this->API_KEY;
            $to = new \SendGrid\Email("", $recipient[$usersEmailColumn]);

            //replaces strings with coresponding user information from the table
            $message = strtr($messageBody, array_combine(
                array_map(function ($k) {return '@' . $k;}, array_keys($recipient)),
                $recipient
            ));
            $content = new \SendGrid\Content("text/html", $message);
            $mail = new \SendGrid\Mail($from, $subject, $to, $content);
            $sg = new \SendGrid($apiKey);
            $response = $sg->client->mail()->send()->post($mail);
        }

        

    }
}
