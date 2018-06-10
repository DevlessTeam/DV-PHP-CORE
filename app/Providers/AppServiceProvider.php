<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\Service\ServiceRepositoryInterface;
use App\Repositories\TableMeta\TableMetaRepositoryInterface;
use App\Repositories\TableMeta\TableMetaRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Blade::directive(
            'datetime',
            function ($expression) {
                return "<?php echo with{$expression}->format('m/d/Y H:i'); ?>";
            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ServiceRepositoryInterface::class,
            ServiceRepository::class
        );

        $this->app->bind(
            TableMetaRepositoryInterface::class,
            TableMetaRepository::class
        );
    }
}
