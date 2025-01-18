<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Support\ViewErrorBag;
// use Illuminate\Support\MessageBag;

class Handler extends ExceptionHandler {
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * This prevents sensitive data such as passwords from being included
     * in the session data during validation failures.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * A list of exception types with their custom log levels.
     *
     * You can define specific log levels for different exception types here.
     *
     * @var array<class-string<\Throwable>, string>
     */
    protected $levels = [
        // Example: \Some\Exception\Class::class => 'warning',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * This method is used to define custom reporting or logging for exceptions.
     */
    public function register(): void {
        $this->reportable(function (Throwable $e) {
            // Add custom logic for reporting exceptions if needed.
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * This method is responsible for converting exceptions into appropriate
     * HTTP responses, such as rendering views or JSON responses for APIs.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception) {
        // Handle specific HTTP exceptions.
        if ($this->isHttpException($exception)) {
            // Additional logic for specific HTTP exceptions can be added here.
        }

        // Default exception handling, using Laravel's parent implementation.
        return parent::render($request, $exception);
    }

    /**
     * Render an HTTP exception into an appropriate response.
     *
     * Tries to use a custom error view if available, otherwise falls back
     * to Laravel's default response handling.
     *
     * @param HttpExceptionInterface $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $exception) {
        // Dynamically register custom error view paths.
        $this->registerErrorViewPaths();

        // Attempt to find and render a custom error view based on the status code.
        if ($view = $this->getHttpExceptionView($exception)) {
            try {
                return response()->view($view, [
                    'errors' => new ViewErrorBag(),
                    'exception' => $exception,
                ], $exception->getStatusCode(), $exception->getHeaders());
            } catch (Throwable $t) {
                // Rethrow the exception if the application is in debug mode.
                if (config('app.debug')) {
                    throw $t;
                }

                // Log the exception if it cannot be rethrown.
                $this->report($t);
            }
        }

        // Fallback to the default exception-to-response conversion.
        return $this->convertExceptionToResponse($exception);
    }

    /**
     * Get the view name for a specific HTTP exception.
     *
     * Checks if a custom error view exists for the HTTP status code and returns
     * its name. If no view exists, returns null.
     *
     * @param HttpExceptionInterface $exception
     * @return string|null
     */
    protected function getHttpExceptionView(HttpExceptionInterface $exception) {
        // Get the HTTP status code from the exception.
        $status = $exception->getStatusCode();

        // Construct the view name based on the status code.
        $view = "errors.{$status}";

        // Check if the view exists in the registered paths.
        if (view()->exists($view)) {
            return $view;
        }

        // Return null if no view is found.
        return null;
    }

    /**
     * Register custom error view paths.
     *
     * Dynamically registers additional paths for custom error views using
     * the RegisterErrorViewPaths class.
     */
    protected function registerErrorViewPaths() {
        // Ensure RegisterErrorViewPaths is implemented correctly.
        (new \App\Exceptions\RegisterErrorViewPaths)();
    }
}
