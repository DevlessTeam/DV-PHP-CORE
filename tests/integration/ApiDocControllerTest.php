<?php

use App\TableMeta;

/**
 * Tests for ApiDocController
 *
 * @covers App\Http\Controllers\ApiDocController
 * @group controllers
 *
 * @author Paul Karikari [@koficodes] <paulkarikari1@gmail.com>
 */
class ApiDocControllerTest extends TestCase
{
    public function testIndex()
    {
        $this->visit('console')
            ->seeStatusCode(200);
        $this->assertViewHas('services');

    }

    /**
     * @dataProvider inputForSchemaTest
     */
    public function testSchema($tableMetaData)
    {
        (new TableMeta)->forceCreate($tableMetaData);

        $this->visit('console/1/devless/user_profile')
            ->seeStatusCode(200)
            ->see("name")
            ->see("ref_table")
            ->see("field_type");
    }

    /**
     * @dataProvider inputForSchemaTest
     */
    public function testEdit($tableMetaData)
    {
        (new TableMeta)->forceCreate($tableMetaData);

        $this->visit('console/1')
            ->seeStatusCode(200)
            ->see('user_profile');
    }

    public function inputForSchemaTest()
    {
        return [
            [
                [
                    'table_name' => 'devless_user_profile',
                    'schema' => json_encode([
                        "name" => "user_profile",
                        "description" => "Table for storing DevLess extended profiles",
                        "field" => '{
                            "name" : "users_id",
                            "field_type" : "reference",
                            "ref_table" : "_devless_users",
                            "default" : null,
                            "required" : false,
                            "validation" : false,
                            "is_unique" : true }',
                    ]),
                    'service_id' => 1,
                ],
            ],
        ];
    }
}
