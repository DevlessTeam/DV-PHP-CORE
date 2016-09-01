<?php

use App\Helpers\DataStore;

class DataStoreTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testQueryData()
    {
            $mock = Mockery::mock('App\Http\Controllers\ServiceController');
            $mock->shouldReceive('assign_to_service')->andReturn("mocked");

//            $result = DataStore::service($this->serviceName,  $this->serviceTable, $mock)
//                ->where()->order()->size()->offset()->queryData();
            //var_dump($result);
            //$this->assertTrue(true);
    }
}
