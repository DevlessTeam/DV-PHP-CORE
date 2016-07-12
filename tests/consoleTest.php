<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use setupTest;
class ApplicationFlowTest extends TestCase
{
    /**
     * Test complete application flow.
     *
     * @return void
     */
    public function testCreatService()
    {
        $this->visit('/services/create')
             ->see('ADD SERVICE')
             ->type($this->serviceName, 'name')   
             ->press('Create')
             ->see('Service Created Successfully');
                
    }
    
    public function testServices()
    {
//        $this->visit('/')
//            ->type(env('TEST_LOGIN_EMAIL'), 'email')
//            ->type(env('TEST_LOGIN_PASSWORD'), 'password')
//            ->press('Login')
//            ->see('Create Service');
//      
//        $this->visit('/services/create')
//             ->see('service name');
    }
}


