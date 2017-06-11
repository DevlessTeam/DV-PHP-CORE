<?php

namespace App\Helpers;

use Session;
use App\User as user;

use App\Http\Controllers\ServiceController;

/*
* @author Eddymens <eddymens@devless.io
*/

class DevlessHelper extends Helper
{
    use devlessAuth, directoryHelper, serviceHelper;

    /**
     * set paramters for notification plate.
     *
     * @param type        $message
     * @param type|string $message_color
     */
    public static function flash($message, $message_color = '#736F6F')
    {
        $custom_colors =
            [
                'error' => '#EA7878',
                'warning' => '#F1D97A',
                'success' => '#7BE454',
                'notification' => '#736F6F',
            ];
        (isset($custom_colors[$message_color])) ? $notification_color =
            $custom_colors[$message_color]
            : $notification_color = $message_color;

        session::flash('color', $notification_color);
        session::flash('flash_message', $message);
    }

    /**
     * Delete table is exists.
     *
     * @param $serviceName
     * @param $tableName
     *
     * @return bool
     */
    public static function purge_table($serviceName, $tableName)
    {
        $service = new ServiceController();

        return DataStore::service($serviceName, $tableName, $service)->drop() ? true : false;
    }

    /**
     * convert string to json.
     *
     * @param $incomingArray
     *
     * @return string
     *
     * @internal param $incommingArray
     * @internal param $service_components
     */
    public static function convert_to_json($incomingArray)
    {
        $formatted_json = json_encode($incomingArray, true);

        return $formatted_json;
    }

    /**
     * Check method access type.
     *
     * @param $method
     * @param $class
     */
    public static function rpcMethodAccessibility($class, $method)
    {
        $property = $class->getMethod($method);
        $docComment = $property->getDocComment();

        $access_type = function () use ($docComment) {
            (strpos(($docComment), '@ACL private')) ? Helper::interrupt(627) :
                (strpos($docComment, '@ACL protected')) ? Helper::get_authenticated_user_cred(2) :
                    (strpos($docComment, '@ACL public')) ? true : Helper::interrupt(638);
        };

        $access_type();
    }

    /**
     * Get table name from payload.
     *
     * @param $payload
     *
     * @return string
     */
    public static function get_tablename_from_payload($payload)
    {
        if (strtoupper($payload['method']) == 'GET') {
            $tableName = (isset($payload['params']['table'])) ? $payload['params']['table']
                        : '';
        } else {
            $tableName = (isset($payload['params'][0]['name'])) ? $payload['params'][0]['name']
                : '';
        }
        if (is_array($tableName)) {
            $tableName = $tableName[0];
        }

        return $tableName;
    }

    /**
     * Get table name from payload.
     *
     * @param string $service_name
     * @param string $affected_tables
     * @param array  $accessed_table
     * @param string $token
     *
     * @return Exception
     */
    public static function serviceAuth($service_name, $auth_table, $affected_tables, $accessed_table)
    {
        $headers = [];
        foreach (getallheaders() as $header_key => $header_value) {
            $headers[strtolower($header_key)] = $header_value;
        }
        $token = (isset($headers['devless-user-token'])) ? $headers['devless-user-token'] : 'NA';

        $token_reference = $service_name.'_'.$auth_table.'_'.$token;

        if (in_array($accessed_table, $affected_tables)) {
            self::did_custom_login($token_reference, $token) ?: Helper::interrupt(628);
        }

        return true;
    }

    /**
     * Check if user token is available.
     *
     * @param $payload
     *
     * @return string
     */
    public static function did_custom_login($toke_reference, $token)
    {
        $ds = new DataStore();

        if ($ds->getDump($toke_reference) == $token) {
            return true;
        }

        return false;
    }

    /**
     * Check for closest word in an array.
     *
     * @param $word
     * @param $options
     *
     * @return string
     */
    public static function find_closest_word($word, $options)
    {
        $best_closesness = 1;
        $closest_word = null;
        foreach ($options as $each_option) {
            $levenshtein_count = levenshtein($word, $each_option);
            if ($levenshtein_count <= $best_closesness) {
                $closest_word = $each_option;
                $levenshtein_count = $best_closesness;
            }
        }

        return $closest_word;
    }
    /**
     * Script template generated for rules in each new service
     * @return string
     */
    public static function script_template()
    {
        return 
                '
/**
* All service db request pass through here before and after db actions are made
* This makes it possible to modify  either the request or response to your satisfaction
* Learn more about how to do this from the docs 
**/
 -> beforeQuerying()
 -> beforeUpdating()
 -> beforeDeleting()
 -> beforeCreating()

 -> onQuery()
 -> onUpdate()
 -> onDelete()
 -> onCreate()
 
 -> afterQuerying()
 -> afterUpdating()
 -> afterDeleting()
 -> afterCreating()
 ';
 
    }
}
