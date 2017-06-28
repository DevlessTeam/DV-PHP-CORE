<?php
/**
 * Created by PhpStorm.
 * User: eddymens
 * Date: 06/09/2016
 * Time: 10:53 AM.
 */

namespace App\Helpers;

use BadMethodCallException;

class Assert extends Helper
{
   
    /**
     * check if $value is an integer
     *
     * @param $value
     *
     * @return $this
     */
    public static function anInteger($value) 
    {
        return (is_int($value))?:false;
    }

    /**
     * check if $value is a string
     *
     * @param $value
     *
     * @return $this
     */
    public static function aString($value)
    {
        return (is_string($value))?:false;
    }

    /**
     * check if $value is a  boolean
     *
     * @param $value
     *
     * @return $this
     */
    public static function aBoolean($value)
    {
        return (is_bool($value))?:false;
    }

    /**
     * check if $value is a float
     *
     * @param $value
     *
     * @return $this
     */
    public static function aFloat($value)
    {
        return (is_float($value))?:false;
    }

    /**
     * check if $value is within th range $min $max
     *
     * @param $value
     * @param $min
     * @param $max
     *
     * @return $this
     */
    public static function withinRange($value, $min, $max)
    {
        return ($min < $value && $value < $max)?:false;
    }

    /**
     * check if $value is upppercase
     *
     * @param $value
     *
     * @return $this
     */
    public static function upperCase($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_upper($value);
        setlocale(LC_CTYPE, $locale);

        return (!$valid) ? true : false;
    }

    /**
     * check if $value is lowercase.
     *
     * @param $value
     *
     * @return $this
     */
    public static function lowerCase($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_lower($value);
        setlocale(LC_CTYPE, $locale);

        return (!$valid) ? true : false;
    }

    /**
     * check if $value is alphanumeric
     *
     * @param $value
     *
     * @return $this
     */
    public static function alphanumeric($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_alnum($value);
        setlocale(LC_CTYPE, $locale);

        return (!$valid) ? true : false;
    }

    /**
     * check if $value are alphabets
     *
     * @param $value
     *
     * @return $this
     */
    public static function alphabets($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_alpha($value);
        setlocale(LC_CTYPE, $locale);

        return (!$valid) ? true : false;
    }

    /**
     * check if $value startwith $prefix
     *
     * @param $value
     * @param $prefix
     *
     * @return $this
     */
    public static function startsWith($value, $prefix)
    {
        return (0 === strpos($value, $prefix)) ? true : false;
    }

    /**
     * check if $value ends with suffix
     *
     * @param $value
     * @param $suffix
     *
     * @return $this
     */
    public static function endsWith($value, $suffix)
    {
        return ($suffix == substr($value, -strlen($suffix))) ? true : false;
    }

    /**
     * check if $value is an integer
     *
     * @param $value
     * @param $pattern
     *
     * @return $this
     */
    public static function regex($value, $pattern)
    {
        return (preg_match($pattern, $value)) ? true : false;
    }

    /**
     * check if $value is an email
     *
     * @param $value
     *
     * @return $this
     */
    public static function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }


    /**
     * check if $value is not an empty array or empty string
     *
     * @param $value
     *
     * @return $this
     */
    public static function notEmpty($value)
    {
        if(is_array($value)){return (sizeof($value) != 0)?:false;}
        if(is_string($value)){return (strlen($value) != 0)?:false;}
        return false;
    }

    /**
     * check if $value is contains $subString
     *
     * @param $value
     * @param $subString
     *
     * @return $this
     */
    public static function contains($value, $subString)
    {
        return (false !== strpos($value, $subString)) ? true : false;
    }

    /**
     * check if $value equals $value1
     *
     * @param $value
     * @param $value1
     *
     * @return $this
     */
    public static function equal($value, $value1)
    {
        return ($value == $value1)?:false;
    }

    /**
     * check if $value is not equal to $value1
     *
     * @param $value
     * @param $value1
     *
     * @return $this
     */
    public static function notEqual($value, $value1)
    {
        return ($value =! $value1)?:false;
    }
    /**
     * check if $value is greater than $value1
     *
     * @param $value
     *
     * @return $this
     */
    public static function greaterThan($value, $value1)
    {
        return ($value > $value1)?:false;
    }

    /**
     * check if $value is less than $value1
     *
     * @param $value
     * @param $value1
     *
     * @return $this
     */
    public static function lessThan($value, $value1)
    {
        return ($value < $value1)?:false;
    }

    /**
     * check if $value is greater than or equal to value1
     *
     * @param $value
     * @param $value1
     *
     * @return $this
     */
    public static function greaterThanOrEqualTo($value, $value1)
    {
        return ($value >= $value1)?:false;
    }

    /**
     * check if $value is less than or equal to value1
     *
     * @param $value
     * @param $value1
     *
     * @return $this
     */
    public static function lessThanOrEqualTo($value, $value1)
    {
        return ($value <= $value1)?:false;
    }

    public static function __callStatic($name, $arguments)
    {
         if ($name == 'empty') {
            $method = '_empty';
            return call_user_func_array(array('static', $method), $args);
        }

        throw new BadMethodCallException('No such method: '.$name);
    }

    
    private function __construct()
    {
    }
}
