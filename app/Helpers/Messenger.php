<?php
namespace App\Helpers;

use Session;
/* 
*@author Eddymens <eddymens@devless.io>
 */
use App\Helpers\Helper as Helper;

/**
 * Messenger is a session based result carrier
 * This method sends api response to the api endpoint
 */
class Messenger extends Helper
{
    public static $messengerName = 'devless-output-session';
    
    /**
    * get message payload
    * @return array
    */
    public static function message()
    {
        $messengerHolder= self::$messengerName;
        $message = session($messengerHolder);
        session()->forget($messengerHolder);
        return $message;
        
    }
   
   /**
    * create new message payload
    * @return array
    */
    public static function createMessage($message)
    {
        $messengerName= self::$messengerName;
        return (session()->put($messengerName, $message))? true : false;
           
    }
   
   /**
    * get message payload
    * @return array
    */
    public static function destroyMessage()
    {
        $messengerName= self::$messengerName;
        return (session()->forget($messengerName))? true : false;
           
       
       
    }
}
