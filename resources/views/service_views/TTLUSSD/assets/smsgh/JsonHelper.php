<?php

/**
 * Description of Helper
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class JsonHelper {

    protected static $jsonErrors = array(
        JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH => 'State mismatch',
        JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
        JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
        JSON_ERROR_UTF8 => 'Encoding error occured'
    );

    public static function getJson($obj) {
        if (is_string($obj)) {
            $json = json_decode($obj);
            if (($errcode = json_last_error()) == JSON_ERROR_NONE) {
                return $json;
            }
            throw new Exception('json_decode(): '
            . (isset(self::$jsonErrors[$errcode]) ?
                    self::$jsonErrors[$errcode] : 'Unknown error'));
        }
    }

    public static function toJson($object) {
        $obj = new stdClass;
        if (is_object($object)) {
            foreach (get_class_methods($object) as $meth) {
                if (strncmp($meth, 'set', 3))
                    continue;
                $prop = substr($meth, 3);
                $meth = array($object, 'get' . $prop);
                if (is_callable($meth))
                    $obj->{$prop} = call_user_func($meth);
            }
        }
        return json_encode($obj);
    }

}
