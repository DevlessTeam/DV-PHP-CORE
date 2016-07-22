<?php
namespace App\Helpers;

/* 
*@author Eddymens <eddymens@devless.io>
 */
use App\Helpers\Helper as Helper;

class Response extends Helper
{
    //
    
    public static function respond($status_code, $message = null, $payload = [])
    {
        if ($message == null) {
            (isset(self::$MESSAGE_HEAP[$status_code]))?$message = self::$MESSAGE_HEAP[$status_code]:
            $message ;
        }
        
        $response = [
            'status_code'=>$status_code,
            'message'=>$message,
            'payload'=>$payload
        ];
        return ($response);
    }
}
