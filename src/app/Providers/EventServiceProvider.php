<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider {
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\UserCreated::class => [
            \App\Listeners\SendUserCreatedNotification::class,
        ],
        \App\Events\OrderCreated::class => [
            \App\Listeners\SendOrderCreatedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void {
        //
        // Register the UserObserver for the User model
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        // Register the CompanyObserver for the Company model
        \App\Models\Company::observe(\App\Observers\CompanyObserver::class);
        // Register the ProductObserver for the Product model
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
        // Register the StockMovementObserver for the StockMovement model
        \App\Models\StockMovement::observe(\App\Observers\StockMovementObserver::class);
        // Register the OrderObserver for the Order model
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool {
        return false;
    }
}
