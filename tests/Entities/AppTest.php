<?php

use App\App;

class AppTest extends TestCase
{
    /**
     * @var App
     */
    private $apps;

    /**
     * Test setup
     */
    public function setUp()
    {
        parent::setUp();

        
        $this->artisan('migrate:reset');
        $this->artisan('migrate');

        $this->apps = factory(App::class, 10)->create();
    }

    /**
     * @test
     */
    public function it_should_return_the_count_of_apps_created()
    {
        $apps = App::all();

        $this->assertCount(10, $apps);
    }
}
