<?php

return [
    'cache_enabled' => env('NAVIGATION_CACHE_ENABLED', false),
    'cache_ttl' => 3600, // Cache duration in seconds (1 hour)
    'default_permissions' => ['view'],
    'items' => [],
];
