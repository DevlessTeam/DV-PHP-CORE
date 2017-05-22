<?php

namespace App\Helpers;

use Hash;
use Session;
use App\User;
use Validator;
use App\Helpers\Jwt;
use Response as output;
use App\Helpers\Response as Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
/*
 * @author eddymens <eddymens@devless.io>
*composed of most common used classes and functions
*/

class Helper
{
    use auth, messenger, messageStack, validation;
    
    /**
     * Generate session timestamp.
     *
     * @return bool|string
     */
    public static function session_timestamp()
    {
        return date('Y-m-d H:i:s');
    }
}
