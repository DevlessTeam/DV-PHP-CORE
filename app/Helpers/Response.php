<?php

namespace App\Helpers;

use App\User;
use Hash;
use Response as output;
use Session;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Validator;
use App\Helpers\Jwt;
use App\Helpers\Response as Response;

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

        return $response;
    }
}

