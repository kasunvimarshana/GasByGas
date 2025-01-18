<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NotificationService\NotificationServiceInterface;
use App\Services\NotificationService\NotificationFactory;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);

        $this->app->bind(NotificationServiceInterface::class,
                            fn() => NotificationFactory::create('toastr'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
    }
}
