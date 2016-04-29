<?php
namespace App\Helpers;
/* 
*@author Eddymens <eddymens@devless.io
 */

class Response extends Helper
{
    //
    public static function respond($status_code, $message=null, $payload)
    {
        $response = [
            'status_code'=>$status_code,
            'message'=>$message,
            'payload'=>$payload
        ];
        return json_encode($response);
    }
}
