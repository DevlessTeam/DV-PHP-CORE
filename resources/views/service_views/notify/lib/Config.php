<?php
/**
 * Created by Devless.
 * Author: koficodes
 * Date Created: 20th of January 2017 02:52:24 AM
 * @Service: notify
 * @Version: 1.0
 */

/**
 * This reads in the credentials for each third party service API
 */
namespace Notify;

class Config
{
    
    /**
     * @param <String> <$type>{ variable for the type config to return}
     * @return <Array> ( configuration credentials for Api)
     */
    public static function get($type = '')
    {
        if (!$type) {
            return null;
        }

        $smsConfig = \DB::table('notify_sms_config')->first();
        $emailConfig = \DB::table('notify_email_config')->first();
        $pushConfig = \DB::table('notify_push_config')->first();

        
        $configTree = 

        ['sms' => ['accountId' => ($smsConfig->account_id)?$smsConfig->account_id : '',
            'authToken' => ($smsConfig->auth_token) ?$smsConfig->auth_token : '',
            'senderNumber' => ($smsConfig->sender_number) ?$smsConfig->sender_number : ''],

            'email' => ['api_key' => ($emailConfig->api_key) ? $emailConfig->api_key : '',
                'sender_name' =>  ($emailConfig->sender_name) ? $emailConfig->sender_name : '',
                'sender_email' => ($emailConfig->sender_email) ? $emailConfig->sender_email : ''],

            'push' => ['app_id' =>  ($pushConfig->app_id) ? $pushConfig->app_id : '',
                'app_key' => ($pushConfig->app_key) ? $pushConfig->app_key : '',
                'app_secret' => ($pushConfig->app_secret) ? $pushConfig->app_secret : '',
                'app_general_broadcast_channel' =>  ($pushConfig->broadcast_channel) ? $pushConfig->broadcast_channel : '' ,
                'app_general_broadcast_event' => ($pushConfig->broadcast_event) ? $pushConfig->broadcast_event : '',
                'app_option' => ['encrypted' => ($pushConfig->app_options) ? (bool)$pushConfig->app_options  : ''],

            ],
        ];
        
        return $configTree[$type];
    }
}
;
