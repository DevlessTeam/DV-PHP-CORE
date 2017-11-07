<?php

class SetupTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_should_create_a_user_with_a_service()
    {
        //cleans up the migration made by Super class Setup method
        \Schema::dropIfExists($this->serviceName . '_' . $this->serviceTable);
        Artisan::call('migrate:reset', ["--force" => true]);
        Artisan::call('migrate', ["--force" => true]);

        //setup Devless
        $this->visit('/setup')
            ->type('test@test.com', 'email')
            ->type('password', 'password')
            ->type('password', 'password_confirmation')
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
