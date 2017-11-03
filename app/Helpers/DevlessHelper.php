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
            if(self::is_admin_login()) {return true;
            }
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
     * Check for closest word in an array.
     *
     * @param $word
     * @param $options
     *
     * @return string
     */
    public static function find_closest_word($word, $options)
    {
        $best_closesness = -1;
        $closest_word = null;        
        
        foreach ($options as $each_option) {
            $lev = levenshtein($word, $each_option);
            if ($lev == 0) {
                $closest_word = $each_option;
                $best_closesness = 0;
                break;
            }
            if ($lev <= $best_closesness || $best_closesness < 0) {
                $closest_word  = $each_option;
                $best_closesness = $lev;
            }
        }
        return $closest_word;
    }
    /**
     * Script template generated for rules in each new service
     * @return string
     */
    public static function script_template($template_type = 'default')
    {
        $templates = [
            'default' =>
                '
/**
* <?
* Rules allow you to establish control over the flow of 
* your data in and out of the database.
* For example if you will like to change the output message 
* your users receive after quering for data,
* its as easy as `afterQuerying()->mutateResponseMessage("to something else")`. 
* To view the list of callable method append ->help() to a 
* flow statement ie ->beforeQuering()->help() and view from your app.
**/
 -> beforeQuerying()
 -> beforeUpdating()
 -> beforeDeleting()
 -> beforeCreating()

 -> onQuery()
 -> onUpdate()
 -> onDelete()
 -> onCreate()

 -> onAnyRequest()

 -> afterQuerying()
 -> afterUpdating()
 -> afterDeleting()
 -> afterCreating()
 ',

 'devless' =>
                 '
/**
* <?
* Rules allow you to establish control over the flow of 
* your data in and out of the database.
* For example if you will like to change the output message 
* your users receive after quering for data,
* its as easy as `afterQuerying()->mutateResponseMessage("to something else")`. 
* To view the list of callable method append ->help() to a 
* flow statement ie ->beforeQuering()->help() and view from your app.
**/
 -> onTable("")
'];
 return $templates[$template_type];
    }
}
