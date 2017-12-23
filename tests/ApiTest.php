<?php

class ApiTest extends TestCase
{
    private $apiUrl;

    private $subUrl;

    private $dbUrl;

    private $scriptUrl;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate');

        $this->apiUrl = '/api/v1/';
        $this->subUrl = $this->apiUrl . 'service/';
        $this->dbUrl = 'db/';
        $this->scriptUrl = '/script/';
    }

   
    
    /**
     * @test
     */
    public function it_should_check_app_Health()
    {
        $subUrl = $this->apiUrl;

        $this->visit($subUrl . 'status')
            ->see('healthy');
    }
    /**
     * @test
     */
    public function it_should_create_schema()
    {
        $url = $this->subUrl;
        $serviceName = $this->serviceName;

        $schemaStruct = '{  
            "resource":[
                {
                    "name":"' . $this->serviceTable . '",
                    "description":"demo table",
                    "field":[
                        {
                            "name":"username",
                             "field_type":"text",
                             "default":null,
                             "required":true,
                             "is_unique":true,
                             "ref_table":"",
                             "validation":true
                        },
                        {
                             "name":"password",
                             "field_type":"password",
                             "default":null,
                             "required":true,
                             "is_unique":false,
                             "ref_table":"",
                             "validation":true
                        }
                    ]
                }
            ]
        }';

        $schemaObj = json_decode($schemaStruct, true);
        $this->json('POST', $url . $serviceName . '/schema', $schemaObj)
            ->seeJsonEquals(['message' => 'Created table successfully',
                'payload' => [], 'status_code' => 606]);

        // to be moved to testAddData
        $url = $this->subUrl;
        $dbAction = $this->dbUrl;
        $serviceName = $this->serviceName;

        $schemaStruct = '{
            "resource":[
                {
                    "name":"' . $this->serviceTable . '",
                    "field":[
                        {
                            "username":"Edmond",
                            "password":"password"
                        }
                    ]
                }
            ]
        }';

        $schemaObj = json_decode($schemaStruct, true);
        $this->json('POST', $url . $serviceName . '/' . $dbAction, $schemaObj)
            ->seeJson(['message' => 'Data has been added to serviceTable table successfully',
                'payload'=>['entry_id' => 1], 'status_code' => 609,]);


      
    }

    /*
    * @test
    */
    public function it_should_drop_tables()
    {
        $url = $this->subUrl;

        $dbAction = $this->dbUrl;

        $serviceName = $this->serviceName;

        $deleteStruct = '{"resource":[{"name":"' . $this->serviceTable . '","params":[{"delete":true,"where":"id,=,1"}]}]}';

        $deleteObj = json_decode($deleteStruct, true);

        $this->json('DELETE', $url . $serviceName . '/' . $dbAction, $deleteObj)
            ->seeJson(['message' => 'Data / table / field has been deleted',
                'payload' => [], 'status_code' => 636]);

        $deleteStruct = '{"resource":[{"name":"' . $this->serviceTable . '","params":[{"drop":true}]}]}';

        $deleteObj = json_decode($deleteStruct, true);

        $this->json('DELETE', $url . $serviceName . '/' . $dbAction, $deleteObj)
            ->seeJson(['message' => 'dropped table successfully',
                'payload' => [], 'status_code' => 613]);
    }
}
