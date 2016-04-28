<?php
namespace App\Helpers;
/* 
*@author Eddymens <eddymens@devless.io
 */

class Response implements Helper
{
    //
    public static function responsed($status_code, $message, $payload)
    {
        $response = [
            'status_code'=>$status_code,
            'message'=>$message,
            'payload'=>$payload
        ];
        return json_encode($response);
    }
}
