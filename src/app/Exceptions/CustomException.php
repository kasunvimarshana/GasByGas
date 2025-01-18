<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Exception;

class CustomException extends Exception {
    protected array $context = [];
    protected int $statusCode = 400;
    protected string $errorView = 'errors.custom';
    protected string $errorType = 'Application Error';

    /**
     * Constructor for CustomException.
     *
     * @param string $message Exception message.
     * @param mixed ...$options Variadic options for dynamic properties.
     */
    public function __construct(string $message, ...$options) {
        parent::__construct($message);

        // Dynamically assign recognized properties
        foreach ($options as $key => $value) {
            // match ($key) {
            //     'statusCode' => $this->statusCode = (int) $value,
            //     'errorView' => $this->errorView = (string) $value,
            //     'errorType' => $this->errorType = (string) $value,
            //     default => $this->context[$key] = $value,
            // };

            if (property_exists($this, $key)) {
                $this->$key = $value;
            } else {
                // Unrecognized keys are added to the context
                $this->context[$key] = $value;
            }
        }
    }

    /**
     * Retrieve the HTTP status code.
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * Retrieve the error view.
     */
    public function getErrorView(): string {
        return $this->errorView;
    }

    /**
     * Retrieve the error type.
     */
    public function getErrorType(): string {
        return $this->errorType;
    }

    /**
     * Retrieve the exception context.
     */
    public function getContext(): array {
        return $this->context;
    }

    /**
     * Log the exception with dynamic context.
     */
    protected function logException(): void {
        Log::error($this->errorType . ': ' . $this->getMessage(), array_merge([
            'exception' => $this->getTraceAsString(),
            'user' => auth()->check() ? auth()->id() : 'guest',
            'status_code' => $this->getStatusCode(),
        ], $this->getContext()));
    }

    /**
     * Report the exception.
     */
    public function report(): void {
        $this->logException();
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request) {
        return $request->expectsJson()
            ? $this->renderJsonResponse()
            : $this->renderViewResponse();
    }

    /**
     * Render JSON response for API or AJAX requests.
     */
    protected function renderJsonResponse(): JsonResponse {
        return response()->json([
            'error' => $this->errorType,
            'message' => $this->getMessage(),
            'context' => $this->getContext(),
        ], $this->getStatusCode());
    }

    /**
     * Render a view response for regular HTTP requests.
     */
    protected function renderViewResponse(): Response {
        return response()->view($this->getErrorView(), [
            'error' => $this->errorType,
            'message' => $this->getMessage(),
            'context' => $this->getContext(),
        ], $this->getStatusCode());
    }
}
