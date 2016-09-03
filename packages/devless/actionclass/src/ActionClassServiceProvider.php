<?php

namespace Devless\ActionClass;

use Illuminate\Support\ServiceProvider;

class ActionClassServiceProvider extends ServiceProvider
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
        $this->app->make('Devless\ActionClass\ActionClass');
    }
}
