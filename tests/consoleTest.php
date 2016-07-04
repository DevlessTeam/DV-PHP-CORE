<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class applicationFlowTest extends TestCase
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
            ->type("someemailthatyouwontuse@somethingelse.somethingnotused", 
                    'email')
            ->type("something you will not use as your password asfdhvjafhdsjaklfhs", 
                    'password')
            ->press('Login')
            ->see('Incorrect login credentials');
        
        //Successful login
        $this->visit('/')
            ->type(env('TEST_LOGIN_EMAIL'), 'email')
            ->type(env('TEST_LOGIN_PASSWORD'), 'password')
            ->press('Login')
            ->see('Create Service');
            
         return $this;
                
    }
    
    public function testServices()
    {
        $loggedIn = testLogin(); 
        $loggedIn->visit('/services/create')
             ->see('service name');
    }
}


/*test types 
 * Methods,console,apis 
 * console
 * register 
 * create service (crud)
 * create table (crud)
 * add scripts (execution)
 * export an
 * 
 * 
 *  */


//  login validation not working 