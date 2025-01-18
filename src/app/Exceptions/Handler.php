<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Support\ViewErrorBag;
// use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Exceptions\CustomException;

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
            // if (config('app.debug')) {
            //     $this->logException($e);
            // }
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
        // // Handle specific HTTP exceptions.
        // if ($this->isHttpException($exception)) {
        //     // Additional logic for specific HTTP exceptions can be added here.
        // }

        // // Default exception handling, using Laravel's parent implementation.
        // return parent::render($request, $exception);

        // Dynamically handle specific exception types
        return match (true) {
            $exception instanceof CustomException => $this->handleCustomException($request, $exception),
            $exception instanceof \Illuminate\Validation\ValidationException => $this->handleValidationException($request, $exception),
            $exception instanceof \Illuminate\Auth\AuthenticationException => $this->handleAuthenticationException($request, $exception),
            default => parent::render($request, $exception)
        };
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

    /**
     * Dynamically log exceptions with detailed context.
     *
     * @param \Throwable $exception
     */
    protected function logException(Throwable $exception): void {
        Log::error($exception->getMessage(), [
            'exception' => $exception->getTraceAsString(),
            'user' => auth()->check() ? auth()->id() : 'guest',
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'context' => method_exists($exception, 'getContext') ? $exception->getContext() : [],
        ]);
    }

    /**
     * Render either JSON or HTML response based on the request type.
     */
    protected function renderResponse(
        Request $request,
        string $errorType,
        string $message,
        array $context,
        int $statusCode
    ): JsonResponse|Response {
        if ($request->expectsJson()) {
            return $this->renderJsonResponse(
                $errorType,
                $message,
                $context,
                $statusCode
            );
        }

        return $this->renderViewResponse(
            'errors.custom',
            $errorType,
            $message,
            $context,
            $statusCode
        );
    }

    /**
     * Render a JSON response.
     *
     * @param string $errorType
     * @param string $message
     * @param array $context
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function renderJsonResponse(
        string $errorType,
        string $message,
        array $context,
        int $statusCode
    ): JsonResponse {
        return response()->json([
            'error' => $errorType,
            'message' => $message,
            'context' => $context,
        ], $statusCode);
    }

    /**
     * Render a view response.
     *
     * @param string $view
     * @param string $errorType
     * @param string $message
     * @param array $context
     * @param int $statusCode
     * @return \Illuminate\Http\Response
     */
    protected function renderViewResponse(
        string $view,
        string $errorType,
        string $message,
        array $context,
        int $statusCode
    ): Response {
        return response()->view($view, [
            'error' => $errorType,
            'message' => $message,
            'context' => $context,
        ], $statusCode);
    }

    /**
     * Handle CustomException.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exceptions\CustomException  $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function handleCustomException(Request $request, CustomException $exception) {
        $statusCode = $exception->getStatusCode() ?? 400;
        $errorType = $exception->getErrorType() ?? trans('messages.application_error', []);
        $errorView = $exception->getErrorView() ?? 'errors.custom';
        $context = $exception->getContext() ?? [];
        // $request->ajax();

        if ($request->expectsJson()) {
            return $this->renderResponse(
                $request,
                $errorType,
                $exception->getMessage(),
                $context,
                $statusCode
            );
        }

        return $this->renderViewResponse(
            $errorView,
            $errorType,
            $exception->getMessage(),
            $context,
            $statusCode
        );
    }

    /**
     * Handle ValidationException.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function handleValidationException(
        Request $request,
        \Illuminate\Validation\ValidationException $exception
    ) {
        $context = ['errors' => $exception->errors()];

        return $this->renderResponse(
            $request,
            trans('messages.validation_error', []),
            $exception->getMessage(),
            $context,
            $exception->status
        );
    }

    /**
     * Handle AuthenticationException.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function handleAuthenticationException(
        Request $request,
        \Illuminate\Auth\AuthenticationException $exception
    ) {
        if ($request->expectsJson()) {
            return $this->renderResponse(
                $request,
                trans('messages.authentication_error', []),
                $exception->getMessage(),
                [],
                401
            );
        }

        return redirect()->route('login');
    }
}
