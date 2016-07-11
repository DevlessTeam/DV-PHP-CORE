<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost:8000 ';
    public $afterLogin;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite_testing');

        $app = require __DIR__.'/../bootstrap/app.php';
        
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
    
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        
        $this->visit('/setup')
                ->type("test@test.com","email")
                ->type("eddymens","username")
                ->type("password","password")
                ->type("password","password_confirmation")
                ->type("test","app_description")
                ->type("appName","app_name")
                ->press('Create App')
                ->see('Setup successful. Welcome to Devless');
            
         
           
        $this->click('Logout')
            ->type(
                 "password",
                 'password'
            )
            ->type(
                "test@test.com",
                'email'
         )
         ->press('Login')
         ->see('Welcome Back');

        
    }

    public function tearDown()
    {
        //Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
