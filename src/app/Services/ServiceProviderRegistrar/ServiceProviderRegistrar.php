<?php

namespace App\Services\ServiceProviderRegistrar;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Exception;

class ServiceProviderRegistrar {
    /**
     * Register a service provider with the application.
     *
     * @param Application $app
     * @param string $provider
     * @param bool $force
     * @return \Illuminate\Support\ServiceProvider
     * @throws Exception
     */
    public function registerServiceProvider(
        Application $app,
        string $provider,
        bool $force = false
    ): ServiceProvider {
        try {
            // Validate the provider
            if (!class_exists($provider)) {
                throw new Exception("Service provider '{$provider}' does not exist.");
            }

            // Check if the provider is already registered and force registration if necessary
            if (!$force && $app->has($provider)) {
                return $app->make($provider);
            }

            // Register the provider dynamically
            return $app->register($provider, $force);
        } catch (Exception $e) {
            // Log and rethrow the error for visibility
            Log::error('Service provider registration failed: ' . $e->getMessage(), [
                'provider' => $provider,
                'exception' => $e,
            ]);
            throw $e; // Re-throw the exception after logging
        }
    }
}

/*
// Usage
use App\Services\ServiceProviderRegistrar;
use Illuminate\Support\Facades\App;

class SomeController extends Controller {
    protected $serviceProviderRegistrar;

    public function __construct(ServiceProviderRegistrar $serviceProviderRegistrar) {
        $this->serviceProviderRegistrar = $serviceProviderRegistrar;
    }

    public function registerCustomProvider()
    {
        $provider = 'App\\Providers\\CustomServiceProvider';  // The full class name
        $this->serviceProviderRegistrar->registerServiceProvider(App::getInstance(), $provider);
    }
}
*/
