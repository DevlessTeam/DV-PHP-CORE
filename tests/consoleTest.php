<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApplicationFlowTest extends TestCase
{
    /**
     * Test complete application flow.
     *
     * @return void
     */
    public function testLogin()
    {
        //Failed Login
        $this->visit('/')
            ->type(
                "something you will not use as your password asfdhvjafhdsjaklfhs",
                'password'
            )
            ->press('Login')
            ->see('Incorrect login credentials');
        
        //Successful login
        $this->visit('/')
            ->type(env('TEST_LOGIN_EMAIL'), 'email')
            ->type(env('TEST_LOGIN_PASSWORD'), 'password')
            ->press('Login')
            ->see('Create Service');
            
                
    }
    
    public function testServices()
    {
        $this->visit('/')
            ->type(env('TEST_LOGIN_EMAIL'), 'email')
            ->type(env('TEST_LOGIN_PASSWORD'), 'password')
            ->press('Login')
            ->see('Create Service');
      
        $this->visit('/services/create')
             ->see('service name');
    }
}


