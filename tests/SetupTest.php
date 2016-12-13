<?php

use App\User;

class SetupTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate');

        factory(User::class)->create();
    }

    /**
     * @test
     */
    public function it_should_create_a_user_with_an_app()
    {
        //setup Devless
        $this->visit('/setup')
            ->type('test@test.com', 'email')
            ->type('eddymens', 'username')
            ->type('password', 'password')
            ->type('password', 'password_confirmation')
            ->type('test', 'app_description')
            ->type('appName', 'app_name')
            ->press('Create App')
            ->see('Setup successful. Welcome to Devless');

        //login to Devless
        $this->click('Logout')
            ->type('password', 'password')
            ->type('test@test.com', 'email')
            ->press('Login')
            ->see('Welcome Back');

        //create service
        $this->visit('/services/create')
            ->see('ADD SERVICE')
            ->type($this->serviceName, 'name')
            ->press('Create')
            ->see('Service Created Successfully');
    }
}
