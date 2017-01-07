<?php

use App\App;
use App\Jobs\AddAppJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AddAppJobTest extends TestCase
{
    use DispatchesJobs;

    /**
     * @var token
     */
    private $token;

    /**
     * Test setup
     */
    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate:reset');
        $this->artisan('migrate');
        $this->token = App::get_token();
    }

    /**
     * @test
     */
    public function it_should_create_an_app()
    {
        $app = $this->dispatch(new AddAppJob($this->getData()));

        $this->assertCount(1, App::all());
        $this->assertEquals($app->name, 'MyGallery');
        $this->assertEquals($app->description, 'Online shop for artworks');
        $this->assertEquals($app->token, $this->token);
    }

    private function getData()
    {
        return (object)[
            'name' => 'MyGallery',
            'description' => 'Online shop for artworks',
            'token' => $this->token
        ];
    }
}
