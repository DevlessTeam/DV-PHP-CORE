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
   

    public static function anInteger($value) 
    {
        return (is_int($value))?:false;
    }

    public static function aString($value)
    {
        return (is_string($value))?:false;
    }

    public static function aBoolean($value)
    {
        return (is_bool($value))?:false;
    }

    public static function aFloat($value)
    {
        return (is_float($value))?:false;
    }

    public static function withinRange($value, $min, $max)
    {
        return ($min < $value && $value < $max)?:false;
    }

    public static function upperCase($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_upper($value);
        setlocale(LC_CTYPE, $locale);

        return (!$valid) ? true : false;
    }

    public static function lowerCase($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_lower($value);
        setlocale(LC_CTYPE, $locale);

        return (!$valid) ? true : false;
    }

    public static function alphabets($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_alpha($value);
        setlocale(LC_CTYPE, $locale);

        return (!$valid) ? true : false;
    }

    public static function startsWith($value, $prefix)
    {
        return (0 === strpos($value, $prefix)) ? true : false;
    }

    public static function endsWith($value, $suffix)
    {
        return ($suffix == substr($value, -strlen($suffix))) ? true : false;
    }

    public static function regex($value, $pattern)
    {
        return (preg_match($pattern, $value)) ? true : false;
    }

    public static function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }


    public static function notEmpty($value)
    {
        if(is_array($value)){return (sizeof($value) != 0)?:false;}
        if(is_string($value)){return (strlen($value) != 0)?:false;}
        return false;
    }

    public static function contains($value, $subString)
    {
        return (false !== strpos($value, $subString)) ? true : false;
    }

    public static function equal($value, $value1)
    {
        return ($value == $value1)?:false;
    }

    public static function greaterThan($value, $value1)
    {
        return ($value > $value1)?:false;
    }

    public static function lessThan($value, $value1)
    {
        return ($value < $value1)?:false;
    }

    public static function greaterThanOrEqualTo($value, $value1)
    {
        return ($value >= $value1)?:false;
    }

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
