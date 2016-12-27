<?php

use \App\Helpers\DevlessHelper as DLH;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    public $baseUrl = 'http://localhost:8000 ';
    public $serviceName = 'testservice';
    public $serviceTable = 'serviceTable';

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
            ->type(
                'password',
                'password'
            )
            ->type(
                'test@test.com',
                'email'
            )
         ->press('Login')
         ->see('Welcome Back');

         //create service
         $this->visit('/services/create')
             ->see('ADD SERVICE')
             ->type($this->serviceName, 'name')
             ->press('Create')
             ->see('Service Created Successfully');
    }

    public function tearDown()
    {
        DLH::deleteDirectory(config('devless')['views_directory'].$this->serviceName);

        //tear down table in devless-rec after creating for test
        $query = 'DROP TABLE '.$this->serviceName.'_'.$this->serviceTable;
        $db = new SQLite3(database_path('devless-rec.sqlite3'));
        try {
            $db->exec($query);
        } catch (Exception $e) {
            //silence is golden
        }


        parent::tearDown();
    }
}