<?php

use Devless\Schema as Schema;

class DbhandlerTest extends TestCase
{
    private $dbHandler = null;

    private $createSchemaResponse;

    public function setUp()
    {

        parent::setUp();
        $this->dbHandler = new Schema\DbHandler();
        $this->createSchemaResponse = $this->dbHandler->create_schema($this->inputForCreateSchema());

    }
    /**
     * @test
     */
    public function it_should_create_schema()
    {

        $this->assertEquals(606, $this->createSchemaResponse["status_code"]);
    }

    /**
     * @test
     * @depends it_should_create_schema
     * @dataProvider inputForInsertDataIntoDb
     */

    public function it_should_insert_data_into_db($payload)
    {
        $this->withSession(['user' => 1]);
        $response = $this->dbHandler->access_db($payload);
        $this->assertEquals(609, $response["status_code"]);

    }

    /**
     * @test
     * @depends it_should_insert_data_into_db
     * @dataProvider inputForUpdateProvider
     *
     */

    public function it_should_update_db($payload)
    {
        $this->withSession(['user' => 1]);
        $response = $this->dbHandler->access_db($this->inputForInsertDataIntoDb()[0][0]);
        $this->assertEquals(609, $response["status_code"]);
        $response = $this->dbHandler->access_db($payload);
        $this->assertEquals(619, $response["status_code"]);
    }

    /**
     *@test
     * @depends it_should_insert_data_into_db
     * @dataProvider inputForQueryDb
     */

    public function it_should_query_db($payload)
    {

        $this->withSession(['user' => 1]);
        $response = $this->dbHandler->access_db($this->inputForInsertDataIntoDb()[0][0]);
        $this->assertEquals(609, $response["status_code"]);
        $response = $this->dbHandler->access_db($payload);
        $this->assertEquals(625, $response["status_code"]);
    }

    /**
     *@test
     * @depends it_should_insert_data_into_db
     * @dataProvider inputForDeleteRecordProvider
     */

    public function it_should_delete_record_from_db($payload)
    {
        $this->withSession(['user' => 1]);
        $response = $this->dbHandler->access_db($this->inputForInsertDataIntoDb()[0][0]);
        $this->assertEquals(609, $response["status_code"]);
        $response = $this->dbHandler->access_db($payload);
        $this->assertEquals(636, $response["status_code"]);
    }

    public function inputForInsertDataIntoDb()
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
    public function inputForDeleteRecordProvider()
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
