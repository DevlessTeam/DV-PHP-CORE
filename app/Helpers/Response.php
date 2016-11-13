<?php

namespace App\Helpers;

/*
*@author Eddymens <eddymens@devless.io>
 */

/**
 * Format response output.
 */
class Response extends Helper
{
    //

    public static function respond($status_code, $message = null, $payload = [])
    {

        if ($message == null) {
            $message =  (isset(self::$MESSAGE_HEAP[$status_code]))? self::$MESSAGE_HEAP[$status_code] :
            $message;
        }

        $response = [
            'status_code' => $status_code,
            'message'     => $message,
            'payload'     => $payload,
        ];

        //return results from db functions called from scripts
        if (session('script_call') == true) {
            messenger::createMessage($response);
        } else {
            return  $response;
        }

        return $response;
    }
}
