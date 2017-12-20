<?php

/**
 * Created by Devless.
 * Author: koficodes
 * Date Created: 20th of January 2017 02:52:24 AM
 * @Service: notify
 * @Version: 1.0
 */
namespace Notify;

abstract class NotifyBaseClass
{
    public function send($reciepients = [],$messageBody = ''){

    }
    public function sendWithInfo($recipients = [],$recipientIdentifierColumn='', $messageBody = ''){

    }
    public  static function getDataFromRecords($userInfoColumns = '', $serviceName = '', $table = '', $usersIdentifierColumn = '', $conditionArray = [], $limit = 0)
    {
        $userInfoColumns = (empty(trim($userInfoColumns))) ? $userInfoColumns : ',' . $userInfoColumns;
        $query = \DB::table($serviceName . '_' . $table)->select(\DB::raw("{$usersIdentifierColumn}{$userInfoColumns}"));

        $conditionOperators = ['!=', '>=', '<=', '<', '>', '='];
        foreach ($conditionArray as $condition) {
            foreach ($conditionOperators as $operator) {

                if (strpos($condition, $operator)) {
                    list($tableColumn, $compareValue) = explode($operator, $condition);
                    $query->where($tableColumn, $operator, $compareValue);
                    break;
                }
            }
        }
        if ($limit) {
            $query->take($limit);
        }

        $results = collect($query->get())->map(function ($x) {return (array) $x;})->toArray();
        return (empty(trim($userInfoColumns))) ? array_flatten($results) : $results;
    }

}
