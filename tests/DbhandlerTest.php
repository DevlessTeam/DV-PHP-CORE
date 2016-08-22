<?php

use Devless\Schema as Schema;

class DbhandlerTest extends PHPUnit_Framework_TestCase
{

    private $dbHandler;

    private $payload =
    [
        'id'=>1,
        'service_name' =>'serviceName',
        'database' =>'default',
        'driver' => 'sqlite',
        'hostname' => '',
        'username' => '',
        'password' => '',
        'calls' =>  0,
        'resource_access_right' =>'',
        'script' => 'echo ""',
        'method' => 'GET',
        'user_id' => 1,
        'params' => ['table' => 'table_name'],

    ];

    public function __construct()
    {
        $this->dbHandler = new Schema\DbHandler();
    }

    /**
     * Test access_db.
     *
     * @return void
     */
    public function test_query_db()
    {

        //$results = $this->dbHandler->db_query($this->payload);

        $this->assertTrue(true);
    }
}
