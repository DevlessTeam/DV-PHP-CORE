<?php
/**
 * Created by Devless.
 * Author: koficodes
 * Date Created: 20th of January 2017 02:52:24 AM
 * @Service: notify
 * @Version: 1.0
 */
namespace Notify;

require 'pusher/Pusher.php';

use Notify\Config;
use Notify\NotifyBaseClass;

/**
 * @description {sends push notification user Pusher API. www.pusher.com}
 */
class Push extends NotifyBaseClass
{
    private $APP_ID;
    private $APP_KEY;
    private $APP_SECRET;

    public function __construct()
    {
        $pushConfig = Config::get('push');
        $this->APP_ID = $pushConfig['app_id'];
        $this->APP_KEY = $pushConfig['app_key'];
        $this->APP_SECRET = $pushConfig['app_secret'];
        $this->GENERAL_CHANNEL = $pushConfig['app_general_broadcast_channel'];
        $this->GENERAL_EVENT = $pushConfig['app_general_broadcast_event'];
        $this->OPTIONS = $pushConfig['app_option'];
        
    }

    /**
     * @description {sends push notification to specified user channel}
     * @param      <String> <$channel> { channel to recieve push notification}
     * @param      <String> <$event> { event to be triggered at users end when notifation is recieved}
     * @param      <String> <$message> { message body of notification }
     */

    public function send($channel = '', $event = '', $message = '')
    {
        
        $pusher = new \Pusher(
            $this->APP_KEY,
            $this->APP_SECRET,
            $this->APP_ID,
            $this->OPTIONS
        );

        $data['message'] = $message;
        $pusher->trigger((empty($channel)) ? $this->GENERAL_CHANNEL : $channel,
            (empty($event)) ? $this->GENERAL_EVENT : $event, $data);

    }

    /**
     * @description {sends push notification to users with selected user infomation}
     * @param      <Array> <$recipient> { array of user's ppsh channel }
     * @param      <String> <$event> { event to be triggered at users end when notifation is recieved}
     * @param      <String> <$recipientsChannelColumn> { table column contain user channel name }
     * @param      <String> <$message> { message body of notification }
     */
    public function sendWithInfo($recipients = [], $event = '', $recipientsChannelColumn = '', $message = '')
    {

        $pusher = new \Pusher(
            $this->APP_KEY,
            $this->APP_SECRET,
            $this->APP_ID,
            $this->OPTIONS
        );
        foreach ($recipients as $key => $recipient) {

            //replaces strings with coresponding user information from the table
            $data['message'] = strtr($message, array_combine(
                array_map(function ($k) {return '@' . $k;}, array_keys($recipient)),
                $recipient
            ));
            $pusher->trigger($recipient[$recipientsChannelColumn], $event, $data);
        }

    }
}
