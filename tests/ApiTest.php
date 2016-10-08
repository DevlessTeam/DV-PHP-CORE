<?php

class ApiTest extends TestCase
{
    private $apiUrl;
    private $subUrl;
    private $dbUrl;
    private $scriptUrl;

    public function __construct()
    {
        $this->apiUrl = '/api/v1/';

        $this->subUrl = $this->apiUrl.'service/';

        $this->dbUrl = 'db/';

        $this->scriptUrl = '/script/';
    }

    /**
     * Temporal test for complete service creation cycle.
     *
     * @return void
     */
    public function testLog()
    {
        $subUrl = $this->apiUrl;

        $this->visit($subUrl.'log')
             ->see('no log available');
    }

    public function testHealth()
    {
        $subUrl = $this->apiUrl;

        $this->visit($subUrl.'status')
             ->see('healthy');
    }

    public function testSchema()
    {
        $url = $this->subUrl;
        $dbAction = $this->dbUrl;
        $serviceName = $this->serviceName;

        $schemaStruct = '{  
     "resource":[  
        {  
           "name":"'.$this->serviceTable.'",
           "description":" demo table",
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
  }        
';
        $schemaObj = json_decode($schemaStruct, true);

        $this->json('POST', $url.$serviceName.'/schema', $schemaObj)
              ->seeJsonEquals(['message' => 'Created table successfully',
                  'payload'              => [], 'status_code' => 606, ]);


        //to be moved to testAddData
         $url = $this->subUrl;
        $dbAction = $this->dbUrl;
        $serviceName = $this->serviceName;

        $schemaStruct = '{  
                        "resource":[  
                           {  
                              "name":"'.$this->serviceTable.'",
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
        $this->json('POST', $url.$serviceName.'/'.$dbAction, $schemaObj)
              ->seeJson(['message' => 'Data has been added to serviceTable table succefully',
                    'payload'      => [], 'status_code' => 609, ]);


        $url = $this->subUrl;

        $dbAction = $this->dbUrl;

        $serviceName = $this->serviceName;

        $deleteStruct = '{"resource":[{"name":"'.$this->serviceTable.'","params":[{"delete":true,"where":"id,=,1"}]}]}';

        $deleteObj = json_decode($deleteStruct, true);

        $this->json('DELETE', $url.$serviceName.'/'.$dbAction, $deleteObj)
            ->seeJson(['message' => 'The table or field has been delete',
                'payload'        => [], 'status_code' => 636, ]);

        $deleteStruct = '{"resource":[{"name":"'.$this->serviceTable.'","params":[{"drop":true}]}]}';

        $deleteObj = json_decode($deleteStruct, true);

        $this->json('DELETE', $url.$serviceName.'/'.$dbAction, $deleteObj)
            ->seeJson(['message' => 'dropped table successfully',
                'payload'        => [], 'status_code' => 613, ]);
        //dd($this);
    }

    public function testAddData()
    {
        //silence is golden
    }

    public function testGetData()
    {
        //silence is golden
    }

    public function testDestroy()
    {
    }
}
