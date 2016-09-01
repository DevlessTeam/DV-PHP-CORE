<?php
/**
 * Created by PhpStorm.
 * User: eddymens
 * Date: 01/09/2016
 * Time: 1:21 AM
 */

namespace App\Helpers;


use App\Http\Controllers\ServiceController as Service;

class DataStore extends Helper
{
    private static $instance;
    private static $payload;


    /**
     * Specify which service table you want to work with
     * @param $serviceName
     * @param $tableName
     * @param Service $service
     * @return DataStore
     */
    public static function service($serviceName, $tableName, Service $service )
    {

        $setServiceAndTableNames = function () use($serviceName, $tableName, $service){
            self::$payload['service_name'] = $serviceName;
            self::$payload['params'] = ['table' => [$tableName]];
            self::$payload['service'] = $service;

        };



        (isset(self::$payload['service_name'], self::$payload['params']['table']))? true : $setServiceAndTableNames();

        return (is_null(self::$instance))? self::$instance = new self() : self::$instance;
    }

    public static function queryData()
    {
        $service = self::$payload['service'];
        $payload = self::$payload;

        $result  = $service->assign_to_service($payload['service_name'], 'db', 'GET', self::$payload['params']);
        return $result;
    }

    public static function addData($params, $data) {/**/}
    public static function update($params, $data) {/**/}
    public static function delete($params) {/**/}



    public static function where($column, $value)
    {
        return self::bindToParams('where',  $column.','.$value);
    }

    /**
     * Order records by a given field
     * @param $value
     * @return DataStore
     */
    public static function order($value)
    {
        return self::bindToParams('order', $value);
    }

    /**
     * Get a given number of records
     * @param $value
     * @return null
     */
    public static function size($value)
    {
        return self::bindToParams('size', $value);
    }

    /**
     * add parameter to Global params
     * @param $methodName
     * @param $args
     * @return DataStore
     */
    private static function bindToParams($methodName, $args)
    {
        self::$payload['params'][$methodName] =
            (null == isset( self::$payload['params'][$methodName]))?  self::$payload['params'][$methodName] = [] : true;

        array_push(self::$payload['params'][$methodName], $args);

        return (is_null(self::$instance))? self::$instance = new self() : self::$instance;
    }
}

