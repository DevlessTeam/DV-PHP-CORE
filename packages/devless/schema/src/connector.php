<?php

namespace Devless\Schema;

use Illuminate\Http\Request;

trait connector
{
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
        if ($driver == 'sqlite' && empty($database)) {
            $database = database_path('devless-rec.sqlite3');
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

        \Config::set('database.connections.DYNAMIC_DB_CONFIG', $conn);
    }
    /**
     * access different database connections.
     *
     * @param $connector_params
     *
     * @return bool
     *
     * @internal param Request $payload parameters
     */
    private function _connector($connector_params)
    {
        $driver = $connector_params['driver'];

        //get current database else connect to remote
        if ($driver == 'default' || $driver == '') {
            $default_database = config('database.default');
            $default_connector = config('database.connections.'.$default_database);
            $driver = $default_connector['driver'];
            if (isset($default_connector['hostname'])) {
                $hostname = $default_connector['hostname'];
            } else {
                $hostname = (isset($default_connector['host'])) ? $default_connector['host'] : '';
            }
            $username = (isset($default_connector['username'])) ? $default_connector['username'] : '';
            $password = (isset($default_connector['password'])) ? $default_connector['password'] : '';
            $database = $default_connector['database'];
            $port = (isset($default_connector['port'])) ? $default_connector['port'] : '';
        } else {
            $fields = ['driver', 'hostname', 'database', 'username', 'password', 'port'];
            foreach ($fields as $field) {
                ${$field} = $connector_params[$field];
            }
        }
        $this->db_socket($driver, $hostname, $database, $username, $password, $port);

        return true;
    }

    /**
     * Check if a connection can be made to database.
     *
     * @param array $connection_params (hostname,username,password,driver,)
     *
     * @return bool
     */
    public function check_db_connection(array $connection_params)
    {
        $this->_connector($connection_params);

        return true;
    }
}
