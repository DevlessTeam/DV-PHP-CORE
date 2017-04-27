<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommonUtil
 *
 * @author Arsene Tochemey
 */
class CommonUtil {

    protected static $_pattern = array(
        'hostname' => '(?:[_\p{L}0-9][-_\p{L}0-9]*\.)*(?:[\p{L}0-9][-\p{L}0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,})'
    );

    public static function startsWith($needle, $haystack) {
        return ($needle[0] === $haystack);
    }

    public static function endsWith($needle, $haystack) {
        return ($needle[strlen($needle) - 1] === $haystack);
    }

    /**
     * Date validation, determines if the string passed is a valid date.
     * keys that expect full month, day and year will validate leap years
     *
     * ### Formats:
     *
     * - `dmy` 27-12-2006 or 27-12-06 separators can be a space, period, dash, forward slash
     * - `mdy` 12-27-2006 or 12-27-06 separators can be a space, period, dash, forward slash
     * - `ymd` 2006-12-27 or 06-12-27 separators can be a space, period, dash, forward slash
     * - `dMy` 27 December 2006 or 27 Dec 2006
     * - `Mdy` December 27, 2006 or Dec 27, 2006 comma is optional
     * - `My` December 2006 or Dec 2006
     * - `my` 12/2006 or 12/06 separators can be a space, period, dash, forward slash
     * - `ym` 2006/12 or 06/12 separators can be a space, period, dash, forward slash
     * - `y` 2006 just the year without any separators
     *
     * @param string $check a valid date string
     * @param string|array $format Use a string or an array of the keys above.
     *    Arrays should be passed as array('dmy', 'mdy', etc)
     * @param string $regex If a custom regular expression is used this is the only validation that will occur.
     * @return boolean Success
     */
    public static function is_date($check, $format = 'ymd', $regex = null) {
        if ($regex !== null) {
            return self::_check($check, $regex);
        }
        $month = '(0[123456789]|10|11|12)';
        $separator = '([- /.])';
        $fourDigitYear = '(([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))';
        $twoDigitYear = '([0-9]{2})';
        $year = '(?:' . $fourDigitYear . '|' . $twoDigitYear . ')';

        $regex['dmy'] = '%^(?:(?:31(\\/|-|\\.|\\x20)(?:0?[13578]|1[02]))\\1|(?:(?:29|30)' .
                $separator . '(?:0?[1,3-9]|1[0-2])\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:29' .
                $separator . '0?2\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\\d|2[0-8])' .
                $separator . '(?:(?:0?[1-9])|(?:1[0-2]))\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$%';

        $regex['mdy'] = '%^(?:(?:(?:0?[13578]|1[02])(\\/|-|\\.|\\x20)31)\\1|(?:(?:0?[13-9]|1[0-2])' .
                $separator . '(?:29|30)\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:0?2' . $separator . '29\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))' .
                $separator . '(?:0?[1-9]|1\\d|2[0-8])\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$%';

        $regex['ymd'] = '%^(?:(?:(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))' .
                $separator . '(?:0?2\\1(?:29)))|(?:(?:(?:1[6-9]|[2-9]\\d)?\\d{2})' .
                $separator . '(?:(?:(?:0?[13578]|1[02])\\2(?:31))|(?:(?:0?[1,3-9]|1[0-2])\\2(29|30))|(?:(?:0?[1-9])|(?:1[0-2]))\\2(?:0?[1-9]|1\\d|2[0-8]))))$%';

        $regex['dMy'] = '/^((31(?!\\ (Feb(ruary)?|Apr(il)?|June?|(Sep(?=\\b|t)t?|Nov)(ember)?)))|((30|29)(?!\\ Feb(ruary)?))|(29(?=\\ Feb(ruary)?\\ (((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)))))|(0?[1-9])|1\\d|2[0-8])\\ (Jan(uary)?|Feb(ruary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep(?=\\b|t)t?|Nov|Dec)(ember)?)\\ ((1[6-9]|[2-9]\\d)\\d{2})$/';

        $regex['Mdy'] = '/^(?:(((Jan(uary)?|Ma(r(ch)?|y)|Jul(y)?|Aug(ust)?|Oct(ober)?|Dec(ember)?)\\ 31)|((Jan(uary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep)(tember)?|(Nov|Dec)(ember)?)\\ (0?[1-9]|([12]\\d)|30))|(Feb(ruary)?\\ (0?[1-9]|1\\d|2[0-8]|(29(?=,?\\ ((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)))))))\\,?\\ ((1[6-9]|[2-9]\\d)\\d{2}))$/';

        $regex['My'] = '%^(Jan(uary)?|Feb(ruary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep(?=\\b|t)t?|Nov|Dec)(ember)?)' .
                $separator . '((1[6-9]|[2-9]\\d)\\d{2})$%';

        $regex['my'] = '%^(' . $month . $separator . $year . ')$%';
        $regex['ym'] = '%^(' . $year . $separator . $month . ')$%';
        $regex['y'] = '%^(' . $fourDigitYear . ')$%';

        $format = (is_array($format)) ? array_values($format) : array($format);
        foreach ($format as $key) {
            if (self::_check($check, $regex[$key]) === true) {
                return true;
            }
        }
        return false;
    }

    /**
     * Validates a datetime value
     *
     * All values matching the "date" core validation rule, and the "time" one will be valid
     *
     * @param string $check Value to check
     * @param string|array $dateFormat Format of the date part. See Validation::date for more information.
     * @param string $regex Regex for the date part. If a custom regular expression is used this is the only validation that will occur.
     * @return boolean True if the value is valid, false otherwise
     * @see Validation::date
     * @see Validation::time
     */
    public static function is_datetime($check, $dateFormat = 'ymd', $regex = null) {
        $valid = false;
        $parts = explode(' ', $check);
        if (!empty($parts) && count($parts) > 1) {
            $time = array_pop($parts);
            $date = implode(' ', $parts);
            $valid = self::is_date($date, $dateFormat, $regex) && self::is_time($time);
        }
        return $valid;
    }

    /**
     * Time validation, determines if the string passed is a valid time.
     * Validates time as 24hr (HH:MM) or am/pm ([H]H:MM[a|p]m)
     * Does not allow/validate seconds.
     *
     * @param string $check a valid time string
     * @return boolean Success
     */
    public static function is_time($check) {
        return self::_check($check, '%^((0?[1-9]|1[012])(:[0-5]\d){0,2} ?([AP]M|[ap]m))$|^([01]\d|2[0-3])(:[0-5]\d){0,2}$%');
    }

    /**
     * Boolean validation, determines if value passed is a boolean integer or true/false.
     *
     * @param string $check a valid boolean
     * @return boolean Success
     */
    public static function is_boolean($check) {
        $booleanList = array(0, 1, '0', '1', true, false);
        return in_array($check, $booleanList, true);
    }

    /**
     * Validates for an email address.
     *
     * Only uses getmxrr() checking for deep validation if PHP 5.3.0+ is used, or
     * any PHP version on a non-windows distribution
     *
     * @param string $check Value to check
     * @param boolean $deep Perform a deeper validation (if true), by also checking availability of host
     * @param string $regex Regex to use (if none it will use built in regex)
     * @return boolean Success
     */
    public static function is_email($check, $deep = false, $regex = null) {
        if (is_array($check)) {
            extract(self::_defaults($check));
        }

        if ($regex === null) {
            $regex = '/^[\p{L}0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[\p{L}0-9!#$%&\'*+\/=?^_`{|}~-]+)*@' . self::$_pattern['hostname'] . '$/ui';
        }
        $return = self::_check($check, $regex);
        if ($deep === false || $deep === null) {
            return $return;
        }

        if ($return === true && preg_match('/@(' . self::$_pattern['hostname'] . ')$/i', $check, $regs)) {
            if (function_exists('getmxrr') && getmxrr($regs[1], $mxhosts)) {
                return true;
            }
            if (function_exists('checkdnsrr') && checkdnsrr($regs[1], 'MX')) {
                return true;
            }
            return is_array(gethostbynamel($regs[1]));
        }
        return false;
    }

    /**
     * Checks that a value is a valid UUID - http://tools.ietf.org/html/rfc4122
     *
     * @param string $check Value to check
     * @return boolean Success
     */
    public static function is_uuid($check) {
        $regex = '/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/';
        return self::_check($check, $regex);
    }

    /**
     * Runs a regular expression match.
     *
     * @param string $check Value to check against the $regex expression
     * @param string $regex Regular expression
     * @return boolean Success of match
     */
    protected static function _check($check, $regex) {
        if (is_string($regex) && preg_match($regex, $check)) {
            return true;
        }
        return false;
    }

    /**
     * Get the values to use when value sent to validation method is
     * an array.
     *
     * @param array $params Parameters sent to validation method
     * @return void
     */
    protected static function _defaults($params) {
        self::_reset();
        $defaults = array(
            'check' => null,
            'regex' => null,
            'country' => null,
            'deep' => false,
            'type' => null
        );
        $params += $defaults;
        if ($params['country'] !== null) {
            $params['country'] = mb_strtolower($params['country']);
        }
        return $params;
    }

}
