<?php
/**
 * Created by Devless.
 * Author: Add username here
 * Date Created: 31st of March 2018 05:41:06 PM
 * Service: SQLCONNECT
 * Version: 1.3.6
 */

use App\Helpers\ActionClass;
//Action method for serviceName
class SQLCONNECT
{
    public $serviceName = 'SQLCONNECT';

    /**
     * Set Connection Details. This will make it possible to connect to the database ->SQLCONNECTOR($driver,$host,$database,$username,$password,$port) $driver => pgsql, mysql, sqlite.
     * @ACL private
     */
    public function SQLCONNECTOR($driver,$host,$database,$username,$password,$port) 
    {
        $this->db_socket($driver,$host,$database,$username,$password,$port);
    }
    
     /**
     * Query data from an external database SQLGetData($tableName, $params=[]).
     * @ACL private
     */
    public function SQLGetData($tableName, $params=[])
    {
        $builder = DB::connection('SQLCONNECT')->table($tableName);
        foreach($params as $param => $args) {
            // params 
            $builder = $builder->$param(...$args);

        }
        return  $builder->get();
    }

    /**
     * Add record to an external database ->SQLAddData($tableName, $data). 
     * @ACL private
     */
    public function SQLAddData($tableName, $data) 
    {
        return DB::connection('SQLCONNECT')->table($tableName)->insert($data);
    }
    
    /**
     * Update a record in an external database ->SQLUpdateData($tableName, $id, $data, $key='id').
     * @ACL private
     */
    public function SQLUpdateData($tableName, $id, $data, $key='id')
    {
        return DB::connection('SQLCONNECT')->table($tableName)->where($key, $id)->update($data);
    }
    
    
    /**
     * Delete a record from  an external database ->SQLDeleteData($tableName, $id).
     * @ACL private
     */
    public function SQLDeleteData($tableName, $id, $key='id') 
    {
        return DB::connection('SQLCONNECT')->table($tableName)->where($key, $id)->delete();
    }
    
    
  
    /**
     * Devless database connection socket.
     *
     * @param $driver
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     * @param string   $charset
     * @param string   $prefix
     * @param string   $collation
     */
    public function db_socket(
        $driver,
        $host,
        $database,
        $username,
        $password,
        $port,
        $charset = 'utf8',
        $prefix = '',
        $collation = 'utf8_unicode_ci'
    ) {
        if ($driver == 'sqlite') {
            $database = database_path('SQLCONNECT.sqlite3');
        }
        $conn = [
            'driver' => $driver,
            'host' => $host,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => $charset,
            'prefix' => $prefix,
            'port' => $port,
        ];
        if ($driver == 'mysql') {
            $conn['collation'] = $collation;
        } elseif ($driver == 'pgsql') {
            $conn['schema'] = 'public';
        }

        \Config::set('database.connections.SQLCONNECT', $conn);
    }
    /**
     * List out all possible callbale methods as well as get docs on specific method 
     * @param $methodToGetDocsFor
     * @return $this;
     */
    public function help($methodToGetDocsFor=null)
    {
        $serviceInstance = $this;
        $actionClass = new ActionClass();
        return $actionClass->help($serviceInstance, $methodToGetDocsFor=null);   
    }

    /**
     * This method will execute on service importation
     * @ACL private
     */
    public function __onImport()
    {
        //add code here

    }


    /**
     * This method will execute on service exportation
     * @ACL private
     */
    public function __onDelete()
    {
        //add code here

    }


}

