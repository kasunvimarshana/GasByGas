<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NotificationService\NotificationServiceInterface;
use App\Services\NotificationService\NotificationFactory;
use App\Services\ActivityLoggerService\ActivityLoggerInterface;
use App\Services\ActivityLoggerService\ActivityLoggerService;
use App\Services\LocalFileService\LocalFileServiceInterface;
use App\Services\LocalFileService\LocalFileService;
use App\Services\LocaleService\LocaleServiceInterface;
use App\Services\LocaleService\LocaleService;
use App\Services\PageDetailsService\PageDetailsServiceInterface;
use App\Services\PageDetailsService\PageDetailsService;

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

        $this->app->singleton(ActivityLoggerInterface::class,
                                ActivityLoggerService::class);

        $this->app->bind(LocalFileServiceInterface::class,
                            LocalFileService::class);

        $this->app->bind(LocaleServiceInterface::class,
                            LocaleService::class);

        $this->app->bind(PageDetailsServiceInterface::class,
                            PageDetailsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
    }
}
