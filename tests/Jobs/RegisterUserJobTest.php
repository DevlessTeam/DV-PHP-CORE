<?php

use App\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Jobs\RegisterUserJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\User;

class RegisterUserJobTest extends TestCase
{
    use DispatchesJobs;

    /**
     * Test setup
     */
    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate:reset');
        $this->artisan('migrate');
    }

    /**
     * @test
     */
    public function it_should_create_a_new_user_from_request()
    {
        $request = $this->getRequest();

        $user = $this->dispatch(new RegisterUserJob($request));

        $this->assertCount(1, User::all());
        $this->assertCount(1, App::all());
        $this->assertEquals('eddymens', $user->username);
    }

    private function getRequest()
    {
        $faker = \Faker\Factory::create();

        $password = Hash::make('password');

        return new Request([
            'username' => 'eddymens',
            'email' => $faker->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
            'app_name' => $faker->name,
            'app_description' => $faker->paragraph,
            'app_token' => App::get_token(),
            'devless_token' => App::get_token()
        ]);
    }
}