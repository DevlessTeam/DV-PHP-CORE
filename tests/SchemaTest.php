<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SchemaTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSchema()
    {   $method = 'POST';
        $uri    = 'api/v1/';
        $this->json($method, $uri)->seeJsonEqual([
                 'created' => true,
             ]);
        
    }
}
