<?php

namespace Devless\Script;

use Illuminate\Support\ServiceProvider;

class ScriptServiceProvider extends ServiceProvider
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
        $this->app->make('Devless\Script\ScriptHandler');
    }
}
