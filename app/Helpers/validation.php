<?php

namespace App\Helpers;

use Validator;

trait validation
{
    /**
     * convert soft types to validator rules.
     *
     * @var string
     */
    public static $validator_type =
    [
        'boolean' => 'boolean',
        'decimals' => 'numeric',
        'email' => 'email',
        'integer' => 'integer',
        'password' => 'string',
        'percentage' => 'integer',
        'reference' => 'integer',
        'text' => 'string',
        'textarea' => 'string',
        'image'=> 'string',
        'timestamp' => 'string',
        'url' => 'url',
        'base64' => 'alphanum',
        'phone_number' => 'digits_between:9,15',
        'user_timestamp' => 'date_format:Y-m-d H:i:s',
        'json' => 'json'

    ];

    /**
     * check the validility of a field type
     * uses laravel validator.
     *
     * @param string                             $field_value
     * @param string parameters to check against $check_against
     *
     * @return bool
     */
    public static function field_check($field_value, $check_against)
    {
        //convert check against to field_name for err_msg
        $field_name = $check_against;
        
        //check if multiple rules are used
        if (strpos($check_against, '|')) {
            $rules = explode('|', $check_against);
            
            foreach ($rules as $rule) {
                //convert each rule and re-combine
                if (!isset(self::$validator_type[$rule])) {
                    self::interrupt(
                        618,
                        'validator type '.$rule.
                        ' does not exist'
                    );
                }
                $check_against = self::$validator_type[$rule].'|';
            }
        } else {
            //single validator rule convert field type to lowercase
            $check_against = strtolower($check_against);
            
            if (!isset(self::$validator_type[$check_against])) {
                self::interrupt(
                    618,
                    'validator type '.$check_against.
                    ' does not exist'
                );
            }
            $check_against = self::$validator_type[$check_against];
        }
      
        $state = Validator::make(
            [$field_name => $field_value],
            [$field_name => $check_against]
        );
        if ($state->fails()) {
            return false;
        }

        return true;
    }

    /**
     * get url parameters.
     *
     * @return array
     **/
    public static function query_string()
    {
        if (isset($_SERVER['QUERY_STRING'])) {
            $originalQueryString = $_SERVER['QUERY_STRING'];

            $query = explode('&', $originalQueryString);
            $params = [];
            foreach ($query as $param) {
                if ($param !== '') {
                    $querySet =  explode('=', $param, 2);
                    (count($querySet) != 2)?self::interrupt(646):'';
                    list($name, $value) = $querySet;
                    $params[urldecode($name)][] = urldecode($value);
                }
            }

            return $params;
        } else {
            return '';
        }
    }
}
