<?php

use App\Helpers\DataStore;

class DataStoreTest extends TestCase
{
    /**
     * Test service initiator.
     *
     * @return void
     */
    public function testService()
    {
            $mock = Mockery::mock('App\Http\Controllers\ServiceController');
            $mock->shouldReceive('assign_to_service')->andReturn("mocked");

            $dataStore = DataStore::service($this->serviceName,  $this->serviceTable, $mock);

            $payload = DataStore::$payload;

            $this->assertEquals($payload['service_name'], $this->serviceName);
            $this->assertEquals($payload['params']['table'][0], $this->serviceTable);

            return $dataStore;

    }

    public function testSize()
    {

    }
}


// ->where()->order()->size()->offset()->queryData();
//var_dump($result);