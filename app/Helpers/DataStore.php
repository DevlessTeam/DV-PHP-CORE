<?php
/**
 * Created by PhpStorm.
 * User: eddymens
 * Date: 01/09/2016
 * Time: 1:21 AM.
 */

namespace App\Helpers;

use App\Http\Controllers\ServiceController as Service;

class DataStore extends Helper
{
    private static $instance;
    public static $payload;
    private static $resourceType = 'db';
    /**
     * Specify which service table to work with.
     *
     * @param $serviceName
     * @param $tableName
     * @param Service     $service
     *
     * @return DataStore
     */
    public static function service($serviceName, $tableName, Service $service = null)
    {
        $serviceInstance = new Service();
        $service = ($service == null) ? $serviceInstance : $service;
        $serviceDetails = function () use ($serviceName, $tableName, $service) {
            self::$payload['service_name'] = $serviceName;
            self::$payload['params'] = ['table' => [$tableName]];
            self::$payload['service'] = $service;
        };
        (isset(self::$payload['service_name'], self::$payload['params']['table'])) ? true : $serviceDetails();

        return (is_null(self::$instance)) ? self::$instance = new self() : self::$instance;
    }
    /**
     * Query service tables.
     *
     * @return array
     */
    public static function queryData()
    {
        $service = self::$payload['service'];
        $payload = self::$payload;
        $method = 'GET';
        $result =
            $service->assign_to_service($payload['service_name'], self::$resourceType, $method, self::$payload['params'], true);
        self::cleanUp();

        return $result;
    }

    /**
     * Query service tables.
     *
     * @return array
     */
    public static function getData()
    {
        return self::queryData();
    }
    /**
     * Add data to the service table.
     *
     * @param $data
     *
     * @return array
     */
    public static function addData($data)
    {
        $service = self::$payload['service'];
        $payload = self::$payload;
        $tableName = self::$payload['params']['table'][0];
        $method = 'POST';
        $pushToSave = function ($data) use ($tableName, $method, $service, $payload) {
            $dataToAdd = [['name' => $tableName, 'field' => [$data]]];
            self::cleanUp();

            return $service->assign_to_service($payload['service_name'], self::$resourceType, $method, $dataToAdd, true);
        };
        foreach ($data as $datum) {
            $results = $pushToSave($datum);
        }
        self::cleanUp();
        return $results;
    }
    /**
     * Update record at specific entry.
     *
     * @param $data
     *
     * @return array
     */
    public static function update($data)
    {
        $service = self::$payload['service'];
        $payload = self::$payload;
        $tableName = self::$payload['params']['table'][0];
        $method = 'PATCH';
        $dataToPatch =
                [['name' => $tableName, 'params' => [['where' => $payload['params']['where'][0], 'data' => [$data]]]]];
        $result = $service->assign_to_service($payload['service_name'], self::$resourceType, $method, $dataToPatch, true);
        self::cleanUp();

        return $result;
    }
    /**
     * Delete records from service table.
     *
     * @return array
     */
    public static function delete()
    {
        return self::destroyAction('delete');
    }
    /**
     * Truncate service table.
     *
     * @return array
     */
    public static function truncate()
    {
        return self::destroyAction('truncate');
    }
    /**
     * Drop service table.
     *
     * @return array
     */
    public static function drop()
    {
        return self::destroyAction('drop');
    }
    /**
     * select delete action.
     *
     * @param $action
     *
     * @return array
     */
    private static function destroyAction($action)
    {
        $service = self::$payload['service'];
        $payload = self::$payload;
        $tableName = self::$payload['params']['table'][0];
        $method = 'DELETE';
        $parameters = ($action == 'delete' && isset($payload['params']['where'][0])) ?
            [[$action => 'true', 'where' => $payload['params']['where'][0]]] : [[$action => 'true']];
        $deletePayload =
            [['name' => $tableName, 'params' => $parameters]];

        $result = $service->assign_to_service($payload['service_name'], self::$resourceType, $method, $deletePayload, true);
        self::cleanUp();

        return $result;
    }
    /**
     * Skip $value number of results.
     *
     * @param $value
     *
     * @return DataStore
     */
    public function offset($value)
    {
        return self::bindToParams('offset', $value);
    }
    /**
     * Carryout db action where $column equals $value.
     *
     * @param $column
     * @param $value
     *
     * @return DataStore
     */
    public static function where($column, $value)
    {
        return self::bindToParams('where', $column.','.$value);
    }
    /**
     * orWhere to include data that match $value from $column.
     *
     * @param $column
     * @param $value
     *
     * @return DataStore
     */
    public static function orWhere($column, $value)
    {
        return self::bindToParams('orWhere', $column.','.$value);
    }
    /**
     * Order records by a given field.
     *
     * @param $value
     *
     * @return DataStore
     */
    public static function orderBy($value)
    {
        return self::bindToParams('orderBy', $value);
    }
    /**
     * Get a given number of records.
     *
     * @param $value
     */
    public static function size($value)
    {
        return self::bindToParams('size', $value);
    }
    /**
     * Search for a data with $value from $column.
     *
     * @param $column
     * @param $value
     *
     * @return DataStore
     */
    public static function search($column, $value)
    {
        return self::bindToParams('search', $column.','.$value);
    }
    /**
     * add parameter to Global params.
     *
     * @param $methodName
     * @param $args
     *
     * @return DataStore
     */
    private static function bindToParams($methodName, $args)
    {
        self::$payload['params'][$methodName] =
            (null == isset(self::$payload['params'][$methodName])) ? self::$payload['params'][$methodName] = [] : self::$payload['params'][$methodName];
        array_push(self::$payload['params'][$methodName], $args);

        return (is_null(self::$instance)) ? self::$instance = new self() : self::$instance;
    }

    public static function paramsBuilder($methodName, $args)
    {
        return self::bindToParams($methodName, $args);
    }

    /**
     * grab all related tables.
     *
     * @param $value
     *
     * @return mixed
     */
    public static function related($value)
    {
        return self::bindToParams('related', $value);
    }
    private static function cleanUp()
    {
        self::$payload = null;
        self::$instance = null;
    }
    /**
     * get instance information.
     *
     * @return array
     */
    public static function instanceInfo()
    {
        $adminData = \DB::table('users')->where('role', 1)
                ->select('username', 'email')->first();
        $appData = \DB::table('apps')->select('name', 'description', 'token')->first();
        $instanceInfo['app'] = $appData;
        $instanceInfo['admin'] = $adminData;

        return $instanceInfo;
    }

    /**
     * get data from Devless dump.
     *
     * @return bool
     */
    public static function getDump($key)
    {
        $output = \DB::table('devless_dump')->where('key', $key)->first();
        if (!$output) {
            return null;
        }

        return $output->value;
    }

    /**
     * add data to devless dump.
     *
     * @return bool
     */
    public static function setDump($key, $value, $updateIfSet = false)
    {
        try {
            \DB::table('devless_dump')->insert(['key' => $key, 'value' => $value]);    
        } catch(\Exception $exception) {
            return ($updateIfSet) ? self::updateDump($key, $value) : null;
        }
    }

    /**
     * get data from Devless dump.
     *
     * @return bool
     */
    public static function updateDump($key, $value)
    {
        return \DB::table('devless_dump')->where('key', $key)->update(['value' => $value]);
    }

    /**
     * destroy devless dump.
     *
     * @return bool
     */
    public static function destroyDump($key)
    {
        return \DB::table('devless_dump')->where('key', $key)->delete();
    }
}
