<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\View;

class RegisterErrorViewPaths {
    /**
     * Register the "errors" namespace for custom error views.
     */
    public function __invoke() {
        View::replaceNamespace('errors', collect(config('view.paths'))->map(function ($path) {
            return "{$path}/errors";
        })->push(resource_path('views/errors'))->all());
    }
}
