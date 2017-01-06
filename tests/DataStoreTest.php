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
            $serviceMock = Mockery::mock('App\Http\Controllers\ServiceController');
            $serviceMock->shouldReceive('assign_to_service')->with()->andReturn("eddymens");

            $dataStore = DataStore::service($this->serviceName,  $this->serviceTable, $serviceMock);

            $payload = DataStore::$payload;

            $this->assertEquals($payload['service_name'], $this->serviceName);
            $this->assertEquals($payload['params']['table'][0], $this->serviceTable);

            return $dataStore;

    }

    /**
     *  @test
     *  Test size parameter
     *  @return void
     */
    public function it_should_get_size_of_datastore()
    {
            $size = rand();
            $dataStore = $this->it_should_initialize_a_devless_service();
            $dataStore->size($size);
            $params = DataStore::$payload['params'];

            $this->assertTrue(isset($params['size']));
            $this->assertEquals($params['size'][0], $size);

    }

    /**
     * @test
     * Test offset parameter
     * @return void
     */
    public  function it_should_check_the_offset_parameter()
    {
            $offset = rand();
            $dataStore = $this->it_should_initialize_a_devless_service();
            $dataStore->offset($offset);
            $params = DataStore::$payload['params'];

            $this->assertTrue(isset($params['offset']));
            $this->assertEquals($params['offset'][0], $offset);
    }

    /**
     * @test
     * Test order parameter
     * @return void
     */
    public  function it_should_check_orderby_parameter()
    {
        $keyword = rand();
        $dataStore = $this->it_should_initialize_a_devless_service();
        $dataStore->orderBy($keyword);
        $params = DataStore::$payload['params'];

        $this->assertTrue(isset($params['orderBy']));
        $this->assertEquals($params['orderBy'][0], $keyword);
    }
    
    /**
     * @test
     * Test Instance info
     */
    public function it_should_verify_instance_infomation()
    {
        
        $results = DataStore::instanceInfo();

        $this->assertEquals($results['app']->name, 'appName');
        $this->assertEquals($results['admin']->username, 'eddymens');
    
    }
    
    /**
     * @test
     * Test set Dump
     */
    public function it_should_set_dump()
    {
        $setDump = DataStore::setDump('key', 'value');
        
        $this->assertTrue($setDump);
    }
    
    /**
     * @test
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


