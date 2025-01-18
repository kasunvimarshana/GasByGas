<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Services\RolePermissionService\PermissionGateRegistrar;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void {
        // Register policies
        $this->registerPolicies();

        // Delegate gate registration to a dedicated service
        $permissionGateRegistrar = app(PermissionGateRegistrar::class);
        // Since the 'register' method expects a $provider, we pass 'this' (the current provider instance)
        $permissionGateRegistrar->register($this);
    }
}
