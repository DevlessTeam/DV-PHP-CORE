<?php

use App\Helpers\DataStore;

use App\App;
use App\User;

class DataStoreTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        factory(User::class)->create(['username' => 'eddymens']);

        factory(App::class)->create(['name' => 'appName']);
    }

    /**
     * @test
     * Test service initiator.
     * @return DataStore
     */
    public function it_should_initialize_a_devless_service()
    {
        
            // $serviceMock = Mockery::mock('App\Http\Controllers\ServiceController');
            // $serviceMock->shouldReceive('assign_to_service')->with()->andReturn("eddymens");
            $serviceMock = new \App\Http\Controllers\ServiceController();
            $dataStore = DataStore::service($this->serviceName, $this->serviceTable, $serviceMock);

            $payload = DataStore::$payload;

            $this->assertEquals($payload['service_name'], $this->serviceName);
            $this->assertEquals($payload['params']['table'][0], $this->serviceTable);

            return $dataStore;
    }

    /**
     *  @test
     *  @depends it_should_initialize_a_devless_service
     *  Test size parameter
     *  @return void
     */
    public function it_should_get_size_of_datastore($dataStore)
    {
            $size = rand();
            $dataStore->size($size);
            $params = DataStore::$payload['params'];

            $this->assertTrue(isset($params['size']));
            $this->assertEquals($params['size'][0], $size);
    }

    /**
     * @test
     * @depends it_should_initialize_a_devless_service
     * Test offset parameter
     * @return void
     */
    public function it_should_check_the_offset_parameter($dataStore)
    {
            $offset = rand();
            $dataStore->offset($offset);
            $params = DataStore::$payload['params'];

            $this->assertTrue(isset($params['offset']));
            $this->assertEquals($params['offset'][0], $offset);
    }

    /**
     * @test
     * @depends it_should_initialize_a_devless_service
     * Test order parameter
     * @return void
     */
    public function it_should_check_orderby_parameter($dataStore)
    {
        $keyword = rand();
        $dataStore->orderBy($keyword);
        $params = DataStore::$payload['params'];

        $this->assertTrue(isset($params['orderBy']));
        $this->assertEquals($params['orderBy'][0], $keyword);
    }
    
    /**
     * @test
     * @depends it_should_initialize_a_devless_service
     * Test Instance info
     */
    public function it_should_verify_instance_infomation()
    {
        
        $results = DataStore::instanceInfo();
        
        $this->assertEquals($results['app']->name, 'Set app name');
        $this->assertEquals($results['admin']->username, 'Add username here');
    }
    
    /**
     * @test
     * @depends it_should_initialize_a_devless_service
     * Test set Dump
     */
    public function it_should_set_dump()
    {
        $setDump = DataStore::setDump('key', 'value');
        
        $this->assertTrue($setDump);
    }
    
    /**
     * @test
     * @depends it_should_set_dump
     * Test get Dump
     */
    public function it_should_get_dump()
    {
        DataStore::setDump('key', 'value');
        
        $getDump = DataStore::getDump('key');
        
        $this->assertEquals($getDump, 'value');
    }
    
    /**
     * @test
     * @depends it_should_get_dump
     * Test update Dump
     */
    public function it_should_update_dump()
    {
        DataStore::setDump('key', 'value');
        
        DataStore::updateDump('key', 'value2');
        
        $getDump = DataStore::getDump('key');
        
        $this->assertEquals($getDump, 'value2');
    }
    
    /**
     * @test
     * @depends it_should_update_dump
     * Test destroy Dump
     */
    public function it_should_destroy_dump()
    {
        DataStore::setDump('key', 'value');
        
        DataStore::destroyDump('key');
        
        $getDump = DataStore::getDump('key');
        
        $this->assertEquals($getDump, null);
    }
}
