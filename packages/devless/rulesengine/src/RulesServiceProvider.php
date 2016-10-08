<?php

namespace Devless\RulesEngine;

use Illuminate\Support\ServiceProvider;

class RulesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->make('Devless\RulesEngine\Rules');

    }
}
