<?php

namespace App\Helpers;

use Session;

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
