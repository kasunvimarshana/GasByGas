<?php

namespace App\Services\RolePermissionService;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
// use Illuminate\Support\Facades\Auth;
use Exception;

class PermissionGateRegistrar {

    /**
     * Register gates dynamically for all permissions.
     *
     * @param \Illuminate\Support\ServiceProvider|string $provider
     * @param bool $force
     * @return \Illuminate\Support\ServiceProvider
     * @throws Exception
     */
    public function register($provider, $force = false): ServiceProvider {
        try {
            // Dynamically define gates for all permissions
            Permission::all()->each(function ($permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    // return $user->can($permission->name);
                    return $user->hasPermissionTo($permission->name);
                });
            });

            // Resolve the provider if it's a string
            if (is_string($provider)) {
                $provider = app($provider);
            }

            // Ensure the provider is a valid ServiceProvider
            if (!$provider instanceof ServiceProvider) {
                throw new Exception('The provided $provider is not a valid ServiceProvider instance.');
            }

            // Register the service provider if forced
            if ($force) {
                $provider->register();
            }

            // Return the service provider instance
            return $provider;
        } catch (Exception $e) {
            // Log and re-throw any exceptions
            Log::error('Permission GateRegistrar failed: ' . $e->getMessage());
            throw $e;
        }
    }

}
