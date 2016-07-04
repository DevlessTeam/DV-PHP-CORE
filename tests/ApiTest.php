<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
    /**
     * Temporal test for complete service creation cycle.
     *
     * @return void
     */
    public function testLog()
    {
        $this->visit('/api/v1/log')
             ->see('no log available');
             
        
    }
    
    public function testHealth()
    {
        $this->visit('/api/v1/status')
             ->see('healthy');
     
    }
    
    
}
