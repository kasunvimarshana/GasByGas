<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Arr;
// use Illuminate\Support\Facades\Log;
use Exception;

class InputHandler {
    /**
     * Retrieve a sanitized string value.
     */
    public static function getString(
        Request $request,
        string $key,
        ?string $default = null
    ): ?string {
        $value = $request->input($key, $default);
        return is_string($value) ? trim($value) : $default;
    }

    /**
     * Retrieve an integer value.
     */
    public static function getInteger(
        Request $request,
        string $key,
        ?int $default = null
    ): ?int {
        $value = $request->input($key, $default);
        return filter_var($value, FILTER_VALIDATE_INT) !== false ? (int)$value : $default;
    }

    /**
     * Retrieve a floating-point number.
     */
    public static function getFloat(
        Request $request,
        string $key,
        ?float $default = null
    ): ?float {
        $value = $request->input($key, $default);
        return is_numeric($value) ? (float)$value : $default;
    }

    /**
     * Retrieve a boolean value.
     */
    public static function getBoolean(
        Request $request,
        string $key,
        bool $default = false
    ): bool {
        return filter_var($request->input($key, $default), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Retrieve a Carbon date instance.
     */
    public static function getDate(
        Request $request,
        string $key,
        ?string $default = null
    ): ?Carbon {
        $value = $request->input($key, $default);

        try {
            return $value ? Carbon::parse($value) : ($default ? Carbon::parse($default) : null);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Retrieve a formatted date string.
     */
    public static function getFormattedDate(
        Request $request,
        string $key,
        string $format = 'Y-m-d',
        ?string $default = null
    ): ?string {
        $date = self::getDate($request, $key, $default);
        return $date ? $date->format($format) : $default;
    }

    /**
     * Retrieve an array value.
     */
    public static function getArray(
        Request $request,
        string $key,
        ?array $default = []
    ): array {
        $value = $request->input($key, $default);
        return is_array($value) ? $value : Arr::wrap($value);
    }

    /**
     * Retrieve an uploaded file.
     */
    public static function getFile(
        Request $request,
        string $key
    ) {
        return $request->file($key);
    }

    /**
     * Retrieve a valid email address.
     */
    public static function getEmail(
        Request $request,
        string $key,
        ?string $default = null
    ): ?string {
        $value = $request->input($key, $default);
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : $default;
    }

    /**
     * Retrieve JSON data as an array.
     */
    public static function getJson(
        Request $request,
        string $key,
        ?array $default = []
    ): array {
        $value = $request->input($key);
        return is_string($value) ? (json_decode($value, true) ?: $default) : $default;
    }
}
