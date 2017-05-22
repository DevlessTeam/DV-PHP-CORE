<?php

namespace App\Helpers;

use Response as output;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait messenger
{
    /**
     * fetch message based on status code.
     *
     * @param stack $stack
     *
     * @return string or null
     **/
    public static function responseMessage($stack)
    {
        if (isset(self::$MESSAGE_HEAP[$stack])) {
            return self::$MESSAGE_HEAP[$stack];
        } else {
            return;
        }
    }

    /**
     *  Abort execution and show message.
     *
     * @param error code      $stack
     * @param output message  $message
     * @param additional data $payload
     *
     * @return Exception
     */
    public static function interrupt($stack, $message = null, $payload = [])
    {
        $message = ($message !== null) ? $message : self::responseMessage($stack);
        throw new HttpException(500, $message, null, $payload, $stack);
    }
}
