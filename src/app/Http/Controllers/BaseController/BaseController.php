<?php

namespace App\Http\Controllers\BaseController;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Throwable;
use Exception;
use InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Services\NotificationService\NotificationServiceInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

abstract class BaseController extends Controller {
    /** @var array Default configuration */
    protected array $config;

    protected NotificationServiceInterface $notificationService;

    /**
     * BaseController constructor.
     *
     * @param NotificationServiceInterface $notificationService
     * @param array $customConfig
     */
    public function __construct(
        NotificationServiceInterface $notificationService,
        array $customConfig = []
    ) {
        $this->notificationService = $notificationService;
        $this->config = array_merge($this->defaultConfig(), $customConfig);
    }

    protected function defaultConfig(): array {
        return  [
            'default_notification_type' => 'info',
            'default_error_message' => trans('messages.unexpected_error_occurred', []),
            'default_success_status_code' => 200,
            'default_error_status_code' => 500,
            'enable_logging' => true,
            'debug_mode' => false
        ];
    }

    /**
     * Handle a successful response for JSON and non-JSON requests.
     *
     * @param string $message
     * @param mixed|null $data
     * @param string|null $redirectRoute
     * @param array $routeParams
     * @param string|null $notificationType
     * @param int|null $statusCode
     * @param string|null $fragment
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function handleResponse(
        string $message,
        $data = null,
        ?string $redirectRoute = null,
        ?array $routeParams = [],
        ?string $notificationType = null,
        ?int $statusCode = null,
        ?string $fragment = null
    ) {
        $notificationType = $notificationType ?? $this->config['default_notification_type'];
        $statusCode = $statusCode ?? $this->config['default_success_status_code'];

        $this->beforeHandleResponse($message, $data, $statusCode);

        if (request()->expectsJson()) {
            $response = $this->formatJsonResponse(true, $message, $data, null, $statusCode);
        } else {
            $this->notify($notificationType, $message);

            // $response = $redirectRoute
            //     ? redirect()->route($redirectRoute, $routeParams)->with('success', $message)
            //     : back()->with('success', $message);

            $response = $redirectRoute
                ? $this->redirectWithFragment(route($redirectRoute, $routeParams), $fragment)->with('success', $message)
                : $this->backWithFragment($fragment)->with('success', $message);
        }

        $this->afterHandleResponse($response);

        return $response;
    }

     /**
     * Handle validation exceptions.
     *
     * @param ValidationException $exception
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function handleValidationException(ValidationException $exception){
        $errors = $exception->errors();

        if (request()->expectsJson()) {
            return $this->formatJsonResponse(
                    false,
                    trans('messages.validation_error', []),
                    null,
                    $errors,
                    422
                );
        }

        return back()->withErrors($errors)->withInput();
    }

    /**
     * Handle and log exceptions for both JSON and non-JSON requests.
     *
     * @param Throwable|Exception $exception
     * @param string|null $friendlyMessage
     * @param string|null $redirectRoute
     * @param array $routeParams
     * @param int|null $statusCode
     * @param string|null $fragment
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function handleException(
        Throwable|Exception $exception,
        ?string $friendlyMessage = null,
        ?string $redirectRoute = null,
        ?array $routeParams = [],
        ?int $statusCode = null,
        ?string $fragment = null
    ) {
        $friendlyMessage = $friendlyMessage ?? $this->config['default_error_message'];
        $statusCode = $statusCode ?? $this->config['default_error_status_code'];

        $this->logException($exception);
        $errorDetails = $this->getExceptionDetails($exception);

        if (request()->expectsJson()) {
            return $this->formatJsonResponse(
                false,
                $friendlyMessage,
                null,
                ['error' => $errorDetails],
                $statusCode
            );
        }

        $this->notify('error', $friendlyMessage);

        // return $redirectRoute
        //     ? redirect()->route(
        //             $redirectRoute,
        //             $routeParams
        //         )->withErrors(['error' => $friendlyMessage])->withInput()
        //     : back()->withErrors(['error' => $friendlyMessage])->withInput();

        return $redirectRoute
            ? $this->redirectWithFragment(route($redirectRoute, $routeParams), $fragment)
                ->withErrors(['error' => $friendlyMessage])
                ->withInput()
            : $this->backWithFragment($fragment)
                ->withErrors(['error' => $friendlyMessage])
                ->withInput();
    }

    /**
     * Format a consistent JSON response structure.
     *
     * @param bool $success
     * @param string $message
     * @param mixed|null $data
     * @param array|null $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function formatJsonResponse(
        bool $success,
        string $message,
        $data = null,
        ?array $errors = null,
        ?int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Notify using the NotificationService.
     *
     * @param string $type
     * @param string $message
     */
    protected function notify(string $type, string $message): void {
        $this->notificationService->notify([
            'type' => $type,
            'message' => $message,
        ]);
    }

    /**
     * Hook for custom logic before handling a response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     */
    protected function beforeHandleResponse(
        string $message,
        $data,
        int $statusCode
    ): void {
        // Extendable in derived classes.
    }

    /**
     * Hook for custom logic after handling a response.
     *
     * @param JsonResponse|\Illuminate\Http\RedirectResponse $response
     */
    protected function afterHandleResponse($response): void {
        // Extendable in derived classes.
    }

    /**
     * Log exceptions if enabled.
     */
    protected function logException(Exception|Throwable $exception): void {
        if ($this->config['enable_logging']) {
            Log::error($exception->getMessage(), [
                'exception' => $exception,
                'trace' => $this->config['debug_mode'] ? $exception->getTraceAsString() : null,
            ]);
        }
    }

    /**
     * Extract error details for non-production environments.
     */
    protected function getExceptionDetails(Exception|Throwable $exception): ?string {
        return (!$this->config['debug_mode'] && app()->isProduction()) ? null : $exception->getMessage();
    }

    /**
     * Update controller configuration dynamically.
     */
    public function updateConfig(array $newConfig): void {
        $this->config = array_merge($this->config, $newConfig);
    }

    /**
     * Retrieve current configuration.
     */
    public function getConfig(): array {
        return $this->config;
    }

    /**
     * Return a view with shared data.
     *
     * @param string $view
     * @param array $data
     * @return \Illuminate\View\View
     */
    protected function renderView(string $view, array $data = []) {
        return view($view, $data);
    }

    /**
     * Redirect to a given route with a fragment.
     *
     * @param string $url
     * @param string|null $fragment
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithFragment(string $url, ?string $fragment = null) {
        $redirect = redirect()->to($url);
        if ($fragment) {
            $redirect->withFragment($fragment);
        }
        return $redirect;
    }

    /**
     * Redirect back with a fragment.
     *
     * @param string|null $fragment
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function backWithFragment(?string $fragment = null) {
        // $redirect = redirect()->back();
        $redirect = back();
        if ($fragment) {
            $redirect->withFragment($fragment);
        }
        return $redirect;
    }

    /**
     * Safely execute a callback within a database transaction.
     *
     * @param callable $callback
     * @param string|null $errorMessage
     * @return mixed
     * @throws Exception
     */
    protected function executeWithTransaction(callable $callback, ?string $errorMessage = null) {
        DB::beginTransaction();
        try {
            $result = $callback();
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            throw new Exception($errorMessage ?? $e->getMessage());
        }
    }

    /**
     * Apply filtering conditions based on the authenticated user's company or user ID.
     *
     * @param \Illuminate\Database\Eloquent\Builder $queryBuilder
     * @param int|null $companyId
     * @param int|null $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterByCompanyOrUser($queryBuilder, ?int $companyId = null, ?int $userId = null) {
        // Get the authenticated user's company ID, if available
        $companyId = $companyId ?? optional(auth()->user()?->company)->id;
        // Get the authenticated user's ID, if available
        $userId = $userId ?? auth()->user()?->id;

        // Ensure that either company ID or user ID exists
        if (!$companyId && !$userId) {
            throw new InvalidArgumentException('Either company ID or user ID must be provided.');
        }

        return $queryBuilder->where(function($query) use ($companyId, $userId) {
            // Apply filter based on company or user
            if ($companyId) {
                // If a company ID is found, filter by related entity type 'Company'
                $query->where('related_entity_id', $companyId)
                    ->where('related_entity_type', \App\Models\Company::class);
            } else {
                // If no company ID is found, filter by related entity type 'User'
                $query->where('related_entity_id', $userId)
                    ->where('related_entity_type', \App\Models\User::class);
            }
        });
    }

    /**
     * Check if the user is authorized based on a gate.
     *
     * @param string $ability The ability name registered in the gate.
     * @param mixed $arguments Optional arguments to pass to the gate.
     * @param string|null $customErrorMessage Optional custom error message.
     * @param bool $throwException Whether to throw an exception on failure (default: true).
     * @return bool True if authorized; otherwise, false or an exception is thrown.
     * @throws AuthorizationException
     */
    protected function checkGate(
        string $ability,
        $arguments = [],
        ?string $customErrorMessage = null,
        bool $throwException = true
    ): bool {
        if (Gate::allows($ability, $arguments)) {
            return true;
        }

        $defaultMessage = trans('messages.unauthorized_action', []);
        $errorMessage = $customErrorMessage ?? $defaultMessage;

        if ($throwException) {
            throw new AuthorizationException($errorMessage);
        }

        return false;
    }
}
