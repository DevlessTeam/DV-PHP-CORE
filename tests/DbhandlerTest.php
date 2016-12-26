<?php

use Devless\Schema as Schema;

class DbhandlerTest extends TestCase
{
    private $dbHandler = null;

    private $createSchemaRespones;

    public function setUp()
    {

        parent::setUp();
        $this->dbHandler = new Schema\DbHandler();
        $this->createSchemaRespones = $this->dbHandler->create_schema($this->inputForCreateSchema());

    }

    public function tearDown()
    {
        \Schema::dropIfExists($this->serviceName . '_' . $this->serviceTable);
        Artisan::call('migrate:reset', ["--force" => true]);
        //parent::tearDown();
    }

    public function testCreateSchema()
    {
        $this->assertEquals(606, $this->createSchemaRespones["status_code"]);
    }

    /**
     * @depends testCreateSchema
     * @dataProvider inputForDataInsertIntDb
     */

    public function testInsertsDataIntoDb($payload)
    {
        $this->withSession(['user' => 1]);
        $response = $this->dbHandler->access_db($payload);
        $this->assertEquals(609, $response["status_code"]);

    }

    /**
     * @depends testInsertsDataIntoDb
     * @dataProvider inputForUpdateProvider
     *
     */

    public function testUpdateData($payload)
    {
        $this->withSession(['user' => 1]);
        $response = $this->dbHandler->access_db($this->inputForDataInsertIntDb()[0][0]);
        $this->assertEquals(609, $response["status_code"]);
        $response = $this->dbHandler->access_db($payload);
        $this->assertEquals(619, $response["status_code"]);
    }

    /**
     * @depends testInsertsDataIntoDb
     * @dataProvider inputForQueryDb
     */

    public function testQuerydb($payload)
    {

        $this->withSession(['user' => 1]);
        $response = $this->dbHandler->access_db($this->inputForDataInsertIntDb()[0][0]);
        $this->assertEquals(609, $response["status_code"]);
        $response = $this->dbHandler->access_db($payload);
        $this->assertEquals(625, $response["status_code"]);
    }

    /**
     * @depends testInsertsDataIntoDb
     * @dataProvider inputForDetroyDbProvider
     */

    public function testDestroyDb($payload)
    {
        $this->withSession(['user' => 1]);
        $response = $this->dbHandler->access_db($this->inputForDataInsertIntDb()[0][0]);
        $this->assertEquals(609, $response["status_code"]);
        $response = $this->dbHandler->access_db($payload);
        $this->assertEquals(636, $response["status_code"]);
    }

    public function inputForDataInsertIntDb()
    {
        return [
            [
                [
                    "id" => 1,
                    "service_name" => $this->serviceName,
                    "database" => "default",
                    "driver" => "sqlite",
                    "hostname" => "",
                    "username" => "test@test.com",
                    "password" => "password",
                    "script_init_vars" => "",
                    "calls" => null,
                    "resource_access_right" => [
                        "query" => 0,
                        "create" => 0,
                        "update" => 0,
                        "delete" => 0,
                        "schema" => 0,
                        "script" => 0,
                        "view" => 0,
                    ],

                    "port" => null,
                    "method" => "POST",
                    "params" => [
                        [
                            "name" => $this->serviceTable,
                            "field" => [
                                [
                                    "user" => "hh",
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function inputForUpdateProvider()
    {
        return [
            [
                [
                    "id" => 1,
                    "service_name" => $this->serviceName,
                    "database" => "",
                    "driver" => "sqlite",
                    "hostname" => "",
                    "username" => "",
                    "password" => "",
                    "script_init_vars" => "",
                    "calls" => null,
                    "resource_access_right" => [
                        "query" => 0,
                        "create" => 0,
                        "update" => 0,
                        "delete" => 0,
                        "schema" => 0,
                        "script" => 0,
                        "view" => 0,
                    ],
                    "port" => "",
                    "method" => "PATCH",
                    "params" => [
                        [
                            "name" => $this->serviceTable,
                            "params" => [
                                [
                                    "where" => "id,1",
                                    "data" => [
                                        [
                                            "user" => "hhhupdate",
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    "user_id" => 1],
            ],
        ];
    }
    public function inputForDetroyDbProvider()
    {
        return [
            [
                [
                    "id" => 1,
                    "service_name" => $this->serviceName,
                    "database" => "",
                    "driver" => "sqlite",
                    "hostname" => "",
                    "username" => "",
                    "password" => "",
                    "script_init_vars" => "",
                    "calls" => null,
                    "resource_access_right" => [
                        "query" => 0,
                        "create" => 0,
                        "update" => 0,
                        "delete" => 0,
                        "schema" => 0,
                        "script" => 0,
                        "view" => 0,
                    ],
                    "port" => "",
                    "method" => "DELETE",
                    "params" => [
                        [
                            "name" => $this->serviceTable,
                            "params" => [
                                [
                                    "delete" => "true",
                                    "where" => "id,1",
                                ],
                            ],
                        ],
                    ],
                    "user_id" => 1,
                ],
            ],
        ];
    }

    public function inputForQueryDb($value = '')
    {
        return [
            [
                [
                    "id" => 1,
                    "service_name" => $this->serviceName,
                    "database" => "",
                    "driver" => "sqlite",
                    "hostname" => "",
                    "username" => "",
                    "password" => "",
                    "script_init_vars" => "",
                    "calls" => null,
                    "resource_access_right" => [
                        "query" => 0,
                        "create" => 0,
                        "update" => 0,
                        "delete" => 0,
                        "schema" => 0,
                        "script" => 0,
                        "view" => 0,
                    ],
                    "port" => "",
                    "method" => "GET",
                    "params" => [
                        "table" => [
                            $this->serviceTable,
                        ], "related" => [
                            "*",
                        ],
                        "where" => [
                            "id,1",
                        ],
                        "size" => [
                            "1",
                        ],
                    ],
                    "user_id" => 1,
                ],
            ],
        ];

    }

    public function inputForCreateSchema()
    {
        return [
            "id" => 1,
            "service_name" => $this->serviceName,
            "database" => "",
            "driver" => "sqlite",
            "hostname" => "",
            "username" => "",
            "password" => "",
            "resource_access_right" => [
                "query" => 0,
                "create" => 0,
                "update" => 0,
                "delete" => 0,
                "schema" => 0,
                "script" => 0,
                "view" => 0,
            ],
            "port" => "",
            "method" => "POST",
            "params" => [
                [
                    "name" => $this->serviceTable,
                    "description" => "serviceTable",
                    "field" => [
                        [
                            "name" => "user",
                            "field_type" => "text",
                            "ref_table" => "",
                            "default" => null,
                            "required" => false,
                            "validation" => false,
                            "is_unique" => false,
                        ],
                    ],
                ],
            ],
        ];
    }

}
