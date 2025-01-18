<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Facades\CauserResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Relations\Relation;


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
use App\Services\PaginationService\PaginationServiceInterface;
use App\Services\PaginationService\PaginationService;
use App\Services\RolePermissionService\RolePermissionServiceInterface;
use App\Services\RolePermissionService\RolePermissionService;

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

        $this->app->bind(PaginationServiceInterface::class,
                            PaginationService::class);

        $this->app->bind(RolePermissionServiceInterface::class,
                            RolePermissionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
        // Globally resolve the causer to the authenticated user
        CauserResolver::resolveUsing(function (Model $subject = null) {
            return auth()->user() ?: null; // Return null if no user is authenticated
        });

        // Sharing Data Across Views
        View::composer('layouts.app', function ($view) {
            $view->with('appName', config('app.name'));
        });

        // Using Eloquent's morphMap for polymorphic relationships to map short names to full class names
        Relation::morphMap([
            'user' => \App\Models\User::class,    // Short name 'user' maps to the full class name
        ]);
    }
}
