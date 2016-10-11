<?php
/**
 * Created by PhpStorm.
 * User: eddymens
 * Date: 06/09/2016
 * Time: 10:53 AM
 */

namespace App\Helpers;

use BadMethodCallException;
use InvalidArgumentException;
use Traversable;

/**
 * Efficient assertions to validate the input/output of your methods.
 *
 * @method static void nullOrString($value, $message = '')
 * @method static void nullOrStringNotEmpty($value, $message = '')
 * @method static void nullOrInteger($value, $message = '')
 * @method static void nullOrIntegerish($value, $message = '')
 * @method static void nullOrFloat($value, $message = '')
 * @method static void nullOrNumeric($value, $message = '')
 * @method static void nullOrBoolean($value, $message = '')
 * @method static void nullOrScalar($value, $message = '')
 * @method static void nullOrObject($value, $message = '')
 * @method static void nullOrResource($value, $type = null, $message = '')
 * @method static void nullOrIsCallable($value, $message = '')
 * @method static void nullOrIsArray($value, $message = '')
 * @method static void nullOrIsTraversable($value, $message = '')
 * @method static void nullOrIsInstanceOf($value, $class, $message = '')
 * @method static void nullOrNotInstanceOf($value, $class, $message = '')
 * @method static void nullOrIsEmpty($value, $message = '')
 * @method static void nullOrNotEmpty($value, $message = '')
 * @method static void nullOrTrue($value, $message = '')
 * @method static void nullOrFalse($value, $message = '')
 * @method static void nullOrEq($value, $value2, $message = '')
 * @method static void nullOrNotEq($value, $value2, $message = '')
 * @method static void nullOrSame($value, $value2, $message = '')
 * @method static void nullOrNotSame($value, $value2, $message = '')
 * @method static void nullOrGreaterThan($value, $value2, $message = '')
 * @method static void nullOrGreaterThanEq($value, $value2, $message = '')
 * @method static void nullOrLessThan($value, $value2, $message = '')
 * @method static void nullOrLessThanEq($value, $value2, $message = '')
 * @method static void nullOrRange($value, $min, $max, $message = '')
 * @method static void nullOrOneOf($value, $values, $message = '')
 * @method static void nullOrContains($value, $subString, $message = '')
 * @method static void nullOrStartsWith($value, $prefix, $message = '')
 * @method static void nullOrStartsWithLetter($value, $message = '')
 * @method static void nullOrEndsWith($value, $suffix, $message = '')
 * @method static void nullOrRegex($value, $pattern, $message = '')
 * @method static void nullOrAlpha($value, $message = '')
 * @method static void nullOrDigits($value, $message = '')
 * @method static void nullOrAlnum($value, $message = '')
 * @method static void nullOrLower($value, $message = '')
 * @method static void nullOrUpper($value, $message = '')
 * @method static void nullOrLength($value, $length, $message = '')
 * @method static void nullOrMinLength($value, $min, $message = '')
 * @method static void nullOrMaxLength($value, $max, $message = '')
 * @method static void nullOrLengthBetween($value, $min, $max, $message = '')
 * @method static void nullOrFileExists($value, $message = '')
 * @method static void nullOrFile($value, $message = '')
 * @method static void nullOrDirectory($value, $message = '')
 * @method static void nullOrReadable($value, $message = '')
 * @method static void nullOrWritable($value, $message = '')
 * @method static void nullOrClassExists($value, $message = '')
 * @method static void nullOrSubclassOf($value, $class, $message = '')
 * @method static void nullOrImplementsInterface($value, $interface, $message = '')
 * @method static void nullOrPropertyExists($value, $property, $message = '')
 * @method static void nullOrPropertyNotExists($value, $property, $message = '')
 * @method static void nullOrMethodExists($value, $method, $message = '')
 * @method static void nullOrMethodNotExists($value, $method, $message = '')
 * @method static void nullOrKeyExists($value, $key, $message = '')
 * @method static void nullOrKeyNotExists($value, $key, $message = '')
 * @method static void nullOrUuid($values, $message = '')
 * @method static void allString($values, $message = '')
 * @method static void allStringNotEmpty($values, $message = '')
 * @method static void allInteger($values, $message = '')
 * @method static void allIntegerish($values, $message = '')
 * @method static void allFloat($values, $message = '')
 * @method static void allNumeric($values, $message = '')
 * @method static void allBoolean($values, $message = '')
 * @method static void allScalar($values, $message = '')
 * @method static void allObject($values, $message = '')
 * @method static void allResource($values, $type = null, $message = '')
 * @method static void allIsCallable($values, $message = '')
 * @method static void allIsArray($values, $message = '')
 * @method static void allIsTraversable($values, $message = '')
 * @method static void allIsInstanceOf($values, $class, $message = '')
 * @method static void allNotInstanceOf($values, $class, $message = '')
 * @method static void allNull($values, $message = '')
 * @method static void allNotNull($values, $message = '')
 * @method static void allIsEmpty($values, $message = '')
 * @method static void allNotEmpty($values, $message = '')
 * @method static void allTrue($values, $message = '')
 * @method static void allFalse($values, $message = '')
 * @method static void allEq($values, $value2, $message = '')
 * @method static void allNotEq($values, $value2, $message = '')
 * @method static void allSame($values, $value2, $message = '')
 * @method static void allNotSame($values, $value2, $message = '')
 * @method static void allGreaterThan($values, $value2, $message = '')
 * @method static void allGreaterThanEq($values, $value2, $message = '')
 * @method static void allLessThan($values, $value2, $message = '')
 * @method static void allLessThanEq($values, $value2, $message = '')
 * @method static void allRange($values, $min, $max, $message = '')
 * @method static void allOneOf($values, $values, $message = '')
 * @method static void allContains($values, $subString, $message = '')
 * @method static void allStartsWith($values, $prefix, $message = '')
 * @method static void allStartsWithLetter($values, $message = '')
 * @method static void allEndsWith($values, $suffix, $message = '')
 * @method static void allRegex($values, $pattern, $message = '')
 * @method static void allAlpha($values, $message = '')
 * @method static void allDigits($values, $message = '')
 * @method static void allAlnum($values, $message = '')
 * @method static void allLower($values, $message = '')
 * @method static void allUpper($values, $message = '')
 * @method static void allLength($values, $length, $message = '')
 * @method static void allMinLength($values, $min, $message = '')
 * @method static void allMaxLength($values, $max, $message = '')
 * @method static void allLengthBetween($values, $min, $max, $message = '')
 * @method static void allFileExists($values, $message = '')
 * @method static void allFile($values, $message = '')
 * @method static void allDirectory($values, $message = '')
 * @method static void allReadable($values, $message = '')
 * @method static void allWritable($values, $message = '')
 * @method static void allClassExists($values, $message = '')
 * @method static void allSubclassOf($values, $class, $message = '')
 * @method static void allImplementsInterface($values, $interface, $message = '')
 * @method static void allPropertyExists($values, $property, $message = '')
 * @method static void allPropertyNotExists($values, $property, $message = '')
 * @method static void allMethodExists($values, $method, $message = '')
 * @method static void allMethodNotExists($values, $method, $message = '')
 * @method static void allKeyExists($values, $key, $message = '')
 * @method static void allKeyNotExists($values, $key, $message = '')
 * @method static void allUuid($values, $message = '')
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Assert extends Helper
{
    public static function string($value)
    {

        return (is_string($value) && !is_numeric($value)) ? true : false;
    }

    public static function stringNotEmpty($value)
    {
        self::string($value);
        self::notEmpty($value);
    }

    public static function integer($value)
    {
        return (is_int($value)) ? true : false;
    }

    public static function float($value)
    {
        return (is_float($value)) ? true : false;
    }

    public static function numeric($value)
    {
        return (is_numeric($value)) ? true : false;
    }

    public static function boolean($value)
    {
        return (is_bool($value)) ? true : false;
    }

    public static function scalar($value)
    {
        return (is_scalar($value)) ? true : false;

    }


    public static function isEmpty($value)
    {
        return (empty($value)) ? true : false;
    }

    public static function notEmpty($value)
    {
        return (!empty($value)) ? true : false;
    }

    public static function isNull($value)
    {
        return (null === $value) ? true : false;
    }

    public static function notNull($value)
    {
        return (null !== $value) ? true : false;
    }

    public static function true($value)
    {
        return (true === $value) ? true : false;
    }

    public static function false($value)
    {
        return (false !== $value) ? true : false;
    }

    public static function eq($value, $value2)
    {
        return ($value2 == $value) ? true : false;
    }

    public static function notEq($value, $value2)
    {
        return ($value2 != $value) ? true : false;
    }

    public static function same($value, $value2)
    {
        return ($value2 === $value) ? true : false;
    }

    public static function notSame($value, $value2)
    {
        return ($value2 !== $value) ? true : false;
    }

    public static function greaterThan($value, $limit)
    {
        return ($value <= $limit) ? true : false;
    }

    public static function greaterThanEq($value, $limit)
    {
        return ($value < $limit) ? true : false;
    }

    public static function lessThan($value, $limit)
    {
        return ($value < $limit) ? true : false;
    }

    public static function lessThanEq($value, $limit)
    {
        return ($value <= $limit) ? true : false;
    }

    public static function range($value, $min, $max)
    {
        return  ($value > $min || $value < $max)? true : false;
    }

    public static function oneOf($value, array $values)
    {
        return (!in_array($value, $values, true)) ? true : false;
    }

    public static function contains($value, $subString)
    {
        return (false === strpos($value, $subString)) ? true : false;
    }

    public static function startsWith($value, $prefix)
    {
        return (0 !== strpos($value, $prefix))? true : false;
    }

    public static function startsWithLetter($value)
    {
        $valid = isset($value[0]);

        if ($valid) {
            $locale = setlocale(LC_CTYPE, 0);
            setlocale(LC_CTYPE, 'C');
            $valid = ctype_alpha($value[0]);
            setlocale(LC_CTYPE, $locale);
        }

        return (!$valid) ? true : false;
    }

    public static function endsWith($value, $suffix)
    {
        return ($suffix !== substr($value, -self::strlen($suffix))) ? true : false;
    }

    public static function regex($value, $pattern)
    {
        return (!preg_match($pattern, $value)) ? true : false;
    }

    public static function alpha($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_alpha($value);
        setlocale(LC_CTYPE, $locale);

        return ($valid) ? true : false;
    }

    public static function digits($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_digit($value);
        setlocale(LC_CTYPE, $locale);

        return ($valid) ? true : false;
    }

    public static function alnum($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_alnum($value);
        setlocale(LC_CTYPE, $locale);

        return ($valid) ? true : false;
    }

    public static function lower($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_lower($value);
        setlocale(LC_CTYPE, $locale);

        return ($valid) ? true : false;
    }

    public static function upper($value)
    {
        $locale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C');
        $valid = !ctype_upper($value);
        setlocale(LC_CTYPE, $locale);

        return ($valid) ? true : false;
    }

    public static function length($value, $length)
    {
        return ($length !== self::strlen($value)) ? true : false;
    }

    public static function minLength($value, $min)
    {
        return (self::strlen($value) < $min) ? true : false;
    }

    public static function maxLength($value, $max)
    {
        return (self::strlen($value) > $max) ? true : false;
    }

    public static function lengthBetween($value, $min, $max)
    {
        $length = self::strlen($value);

        return ($length < $min || $length > $max) ? true : false;
    }

    public static function fileExists($value)
    {
        self::string($value);

        return (!file_exists($value)) ? true : false;
    }

    public static function file($value)
    {
        self::fileExists($value);

        return (!is_file($value)) ? true : false;
    }

    public static function directory($value)
    {
        self::fileExists($value);

        return (!is_dir($value)) ? true : false;
    }

    public static function readable($value)
    {
        return (!is_readable($value)) ? true : false;
    }

    public static function writable($value)
    {
        return (!is_writable($value)) ? true : false;
    }

    public static function classExists($value)
    {
        return (!class_exists($value)) ? true : false;
    }

    public static function subclassOf($value, $class)
    {
        return (!is_subclass_of($value, $class)) ? true : false;
    }

    public static function implementsInterface($value, $interface)
    {
        return (!in_array($interface, class_implements($value))) ? true : false;
    }

    public static function propertyExists($classOrObject, $property)
    {
        return (!property_exists($classOrObject, $property)) ? true : false;
    }

    public static function propertyNotExists($classOrObject, $property)
    {
        return (property_exists($classOrObject, $property)) ? true : false;
    }

    public static function methodExists($classOrObject, $method)
    {
        return (!method_exists($classOrObject, $method)) ? true : false;
    }

    public static function methodNotExists($classOrObject, $method)
    {
        return (method_exists($classOrObject, $method)) ? true : false;
    }

    public static function keyExists($array, $key)
    {
        return (!array_key_exists($key, $array)) ? true : false;
    }

    public static function keyNotExists($array, $key)
    {
        return (array_key_exists($key, $array)) ? true : false;
    }

    public static function uuid($value)
    {
        $value = str_replace(array('urn:', 'uuid:', '{', '}'), '', $value);

        // The nil UUID is special form of UUID that is specified to have all
        // 128 bits set to zero.
        if ('00000000-0000-0000-0000-000000000000' === $value) {
            return;
        }

        return (!preg_match('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/', $value))
            ? true : false;
    }

    public static function __callStatic($name, $arguments)
    {
        if ('nullOr' === substr($name, 0, 6)) {
            if (null !== $arguments[0]) {
                $method = lcfirst(substr($name, 6));
                call_user_func_array(array('static', $method), $arguments);
            }

            return;
        }

        if ('all' === substr($name, 0, 3)) {
            self::isTraversable($arguments[0]);

            $method = lcfirst(substr($name, 3));
            $args = $arguments;

            foreach ($arguments[0] as $entry) {
                $args[0] = $entry;

                call_user_func_array(array('static', $method), $args);
            }

            return;
        }

        throw new BadMethodCallException('No such method: ' . $name);
    }

    protected static function valueToString($value)
    {
        if (null === $value) {
            return 'null';
        }

        if (true === $value) {
            return 'true';
        }

        if (false === $value) {
            return 'false';
        }

        if (is_array($value)) {
            return 'array';
        }

        if (is_object($value)) {
            return get_class($value);
        }

        if (is_resource($value)) {
            return 'resource';
        }

        if (is_string($value)) {
            return '"' . $value . '"';
        }

        return (string)$value;
    }

    protected static function typeToString($value)
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }

    protected static function strlen($value)
    {
        if (!function_exists('mb_detect_encoding')) {
            return strlen($value);
        }

        if (false === $encoding = mb_detect_encoding($value)) {
            return strlen($value);
        }

        return mb_strwidth($value, $encoding);
    }

    private function __construct()
    {
    }
}
