<?php

namespace Devless\SystemClass;

use Illuminate\Support\ServiceProvider;

class SystemClassServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->make('Devless\systemClass');
    }
}
