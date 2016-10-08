<?php

use App\Helpers\DataStore;

class DataStoreTest extends TestCase
{


    /**
     * Test service initiator.
     * @return DataStore
     */
    public function testService()
    {
            $serviceMock = Mockery::mock('App\Http\Controllers\ServiceController');
            $serviceMock->shouldReceive('assign_to_service')->with()->andReturn("eddymens");

            $dataStore = DataStore::service($this->serviceName,  $this->serviceTable, $serviceMock);

            $payload = DataStore::$payload;

            $this->assertEquals($payload['service_name'], $this->serviceName);
            $this->assertEquals($payload['params']['table'][0], $this->serviceTable);

            return $dataStore;

    }

    /**
     *Test size parameter
     *  @return void
     */
    public function testSize()
    {
            $size = rand();
            $dataStore = $this->testService();
            $dataStore->size($size);
            $params = DataStore::$payload['params'];

            $this->assertTrue(isset($params['size']));
            $this->assertEquals($params['size'][0], $size);

    }

    /**
     * Test offset parameter
     * @return void
     */
    public  function testOffset()
    {
            $offset = rand();
            $dataStore = $this->testService();
            $dataStore->offset($offset);
            $params = DataStore::$payload['params'];

            $this->assertTrue(isset($params['offset']));
            $this->assertEquals($params['offset'][0], $offset);
    }

    /**
     * Test order parameter
     * @return void
     */
    public  function testOrderBy()
    {
        $keyword = rand();
        $dataStore = $this->testService();
        $dataStore->orderBy($keyword);
        $params = DataStore::$payload['params'];

        $this->assertTrue(isset($params['orderBy']));
        $this->assertEquals($params['orderBy'][0], $keyword);
    }

    public function testInstanceInfo()
    {
        
    $results = DataStore::instanceInfo();
    
    $this->assertEquals($results['app']->name, 'appName');
    $this->assertEquals($results['admin']->username, 'eddymens');
    
    }
}


